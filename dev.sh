#!/bin/sh
# This script is an example for a Docker-based environment.
# It starts the services, the Vite server in the background,
# and the PHP artisan server in the foreground.

docker compose up -d
npm run dev > /dev/null &
php artisan serve
