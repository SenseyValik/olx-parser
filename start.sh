
#!/bin/bash

PROJECT_ROOT=$(pwd)

echo "Починаємо точкове налаштування прав доступу для Laravel..."
echo "Директорія проекту: $PROJECT_ROOT"

echo ""
echo "Крок 1/2: Надання прав на запис для директорії 'storage/'..."
sudo chmod -R 777 $PROJECT_ROOT/storage
echo "Встановлено 775 для директорії 'storage/'."

echo ""
echo "Крок 2/2: Надання прав на запис для директорії 'bootstrap/cache/'..."
sudo chmod -R 777 $PROJECT_ROOT/bootstrap/cache
echo "Встановлено 775 для директорії 'bootstrap/cache/'."

echo ""
echo "Надання прав на виконання для файлу 'artisan'..."
sudo chmod +x $PROJECT_ROOT/artisan
echo "Файл 'artisan' тепер виконуваний."

echo ""
echo "Точкове налаштування прав доступу завершено!"
echo "Будь ласка, перевірте роботу вашого додатку."