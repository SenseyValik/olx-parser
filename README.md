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


# Test Mails
Change .env credentials
use:
https://mailtrap.io/


# Logs
./storage/logs

php artisan filament:install --panels
php artisan schedule:list