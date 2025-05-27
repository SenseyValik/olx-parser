FROM sail-8.4/app

RUN apt-get update && apt-get install -y cron
WORKDIR /var/www/html
