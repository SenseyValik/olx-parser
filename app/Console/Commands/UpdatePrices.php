<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PriceSubscription;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use DOMDocument;
use DOMXPath;

class UpdatePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse prices from subscribed URLs and send notifications on price changes';

    private Client $client;
    private string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';

    public function __construct()
    {
        parent::__construct();
        
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => $this->userAgent,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'uk-UA,uk;q=0.9,en;q=0.8',
            ]
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting price update process...');

        Log::info('Початок оновлення цін');

        
        // Get all active subscriptions
        $subscriptions = PriceSubscription::where('active', true)->get();
        
        if ($subscriptions->isEmpty()) {
            $this->warn('No active price subscriptions found.');
            return;
        }
        
        $this->info("Found {$subscriptions->count()} active subscriptions");
        
        $processedCount = 0;
        $changedCount = 0;
        $errorCount = 0;
        
        foreach ($subscriptions as $subscription) {
            try {
                $this->processSubscription($subscription, $changedCount);
                $processedCount++;
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Error processing subscription {$subscription->id}: " . $e->getMessage());
                $this->error("Error processing '{$subscription->name}': " . $e->getMessage());
            }
            
            // Delay between requests
            sleep(1);
        }
        
        $this->info("✅ Process completed!");
        $this->info("Processed: {$processedCount}, Changed: {$changedCount}, Errors: {$errorCount}");
    }
    
    /**
     * Process single subscription
     */
    private function processSubscription(PriceSubscription $subscription, int &$changedCount): void
    {
        $this->info("Processing: {$subscription->name}");
        
        // Parse current price
        $currentPrice = $this->parsePrice($subscription->url);
        
        if ($currentPrice === null) {
            $this->warn("Could not find price element");
            return;
        }
        
        $this->info("Current price: {$currentPrice}");
        
        // Get cached price
        $cacheKey = "price_subscription_{$subscription->id}";
        $cachedPrice = Cache::get($cacheKey);
        
        if ($cachedPrice === null) {
            // First time - cache the price
            Cache::forever($cacheKey, $currentPrice);
            $this->info("Price cached for first time");
            return;
        }
        
        // Check if price changed
        if ($cachedPrice !== $currentPrice) {
            $this->warn("Price changed! Old: {$cachedPrice} → New: {$currentPrice}");
            
            // Update cache
            Cache::forever($cacheKey, $currentPrice);
            
            // Send email
            $this->sendEmail($subscription, $cachedPrice, $currentPrice);
            
            $changedCount++;
            $this->info("Email sent to: {$subscription->email}");
        } else {
            $this->info("Price unchanged");
        }
    }
    
    /**
     * Parse price from URL - only [data-testid="ad-price-container"] .css-fqcbii
     */
    private function parsePrice(string $url): ?string
    {
        try {
            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();
            
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            
            $xpath = new DOMXPath($dom);
            
            // Find element: [data-testid="ad-price-container"] .css-fqcbii
            $nodes = $xpath->query('//*[@data-testid="ad-price-container"]//*[contains(@class, "css-fqcbii")]');
            
            if ($nodes && $nodes->length > 0) {
                $priceText = trim($nodes->item(0)->textContent);
                return empty($priceText) ? null : $priceText;
            }
            
            return null;
            
        } catch (RequestException $e) {
            throw new \Exception("HTTP error: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Parse error: " . $e->getMessage());
        }
    }
    
    /**
     * Send email notification
     */
    private function sendEmail(PriceSubscription $subscription, string $oldPrice, string $newPrice): void
    {
        try {
            $subject = "Зміна ціни: {$subscription->name}";
            
            Mail::send('emails.price-changed', [
                'subscription' => $subscription,
                'oldPrice' => $oldPrice,
                'newPrice' => $newPrice,
            ], function ($message) use ($subscription, $subject) {
                $message->to($subscription->email)->subject($subject);
            });
            
        } catch (\Exception $e) {
            throw new \Exception("Email failed: " . $e->getMessage());
        }
    }
}