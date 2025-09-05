#!/bin/bash

echo "Waiting for MySQL to be ready..."
sleep 30

echo "Setting up cron job..."
echo '*/5 * * * * cd /app && sleep 5 && DATABASE_URL="mysql://app:secret@mysql:3306/app?serverVersion=8.0" APP_ENV=dev /usr/local/bin/php bin/console app:update-exchange-rates >> /var/log/cron.log 2>&1' | crontab -

echo "Crontab installed:"
crontab -l

touch /var/log/cron.log

echo "Starting cron daemon..."
cron -f