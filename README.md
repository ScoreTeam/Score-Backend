# Run those commands

cd score_project

cp .env.example .env

composer install

php artisan key:generate

php artisan migrate

php artisan db:seed

php artisan storage:link

php artisan config:cache

php artisan route:cache

php artisan view:cache
