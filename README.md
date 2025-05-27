# Run on Linux
```
sudo ./vendor/bin/sail up -d
```


# Commands for setup
```
docker exec -it olx-parser_laravel.test_1 bash
```
```
php artisan migrate
```
```
composer install
```
```
npm install
```
```
php artisan schedule:run
```

# Docs
[UpdatePrices.php](https://github.com/SenseyValik/olx-parser/blob/main/app/Console/Commands/UpdatePrices.php)
[CRON](https://github.com/SenseyValik/olx-parser/blob/main/routes/console.php)
[Price-update-Model](https://github.com/SenseyValik/olx-parser/tree/main/app/Filament/Resources)

# Video
[Video](https://drive.google.com/file/d/1DSWWv7uOkeGx0XdbG3pZlPuhpcxNW7sl/view?usp=sharing)

# Test Mails
Change .env credentials
use:
https://mailtrap.io/


# Logs
./storage/logs

php artisan filament:install --panels
php artisan schedule:list