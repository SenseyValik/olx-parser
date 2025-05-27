<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Зміна ціни</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .product-name {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }
        .price-change {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
        .price-label {
            font-weight: 500;
            color: #666;
        }
        .old-price {
            color: #e74c3c;
            text-decoration: line-through;
            font-size: 18px;
        }
        .new-price {
            color: #27ae60;
            font-weight: bold;
            font-size: 20px;
        }
        .price-difference {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #e8f5e8;
            border-radius: 6px;
            color: #27ae60;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px auto;
            display: block;
            width: fit-content;
            transition: transform 0.2s ease;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        .emoji {
            font-size: 24px;
            margin-right: 8px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .content {
                padding: 20px 15px;
            }
            .price-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><span class="emoji">🔔</span>Зміна ціни</h1>
        </div>
        
        <div class="content">
            <div class="product-name">
                {{ $subscription->name }}
            </div>
            
            <p>Вітаємо! Ціна на товар, за яким ви стежите, змінилася:</p>
            
            <div class="price-change">
                @if($oldPrice)
                <div class="price-row">
                    <span class="price-label">Попередня ціна:</span>
                    <span class="old-price">{{ $oldPrice }}</span>
                </div>
                @endif
                
                <div class="price-row">
                    <span class="price-label">Нова ціна:</span>
                    <span class="new-price">{{ $newPrice }}</span>
                </div>
                
                @if($oldPrice)
                    @php
                        // Try to extract numeric values to calculate difference
                        preg_match('/[\d,.\s]+/', $oldPrice, $oldMatches);
                        preg_match('/[\d,.\s]+/', $newPrice, $newMatches);
                        
                        if (!empty($oldMatches[0]) && !empty($newMatches[0])) {
                            $oldNum = (float) str_replace([',', ' '], ['', ''], $oldMatches[0]);
                            $newNum = (float) str_replace([',', ' '], ['', ''], $newMatches[0]);
                            $difference = $newNum - $oldNum;
                            $percentChange = $oldNum > 0 ? (($difference / $oldNum) * 100) : 0;
                        }
                    @endphp
                    
                    @if(isset($difference))
                        <div class="price-difference">
                            @if($difference > 0)
                                📈 Ціна зросла на {{ number_format(abs($difference), 0, ',', ' ') }} 
                                ({{ number_format(abs($percentChange), 1) }}%)
                            @elseif($difference < 0)
                                📉 Ціна знизилась на {{ number_format(abs($difference), 0, ',', ' ') }} 
                                ({{ number_format(abs($percentChange), 1) }}%)
                            @endif
                        </div>
                    @endif
                @endif
            </div>
            
            <p>Перейдіть за посиланням, щоб переглянути актуальну інформацію про товар:</p>
            
            <a href="{{ $subscription->url }}" class="button">
                👀 Переглянути товар
            </a>
            
            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                <strong>Примітка:</strong> Ця інформація була автоматично отримана з веб-сайту. 
                Рекомендуємо перевірити актуальну ціну на сайті продавця.
            </p>
        </div>
        
        <div class="footer">
            <p>Цей лист надіслано автоматично системою моніторингу цін.</p>
            <p>Дата: {{ now()->format('d.m.Y H:i') }}</p>
        </div>
    </div>
</body>
</html>