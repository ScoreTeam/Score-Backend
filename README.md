# Enter these commands before the first run

```sh
cd score_project

cp .env.example .env

composer install

php artisan storage:link

php artisan key:generate

php artisan migrate:fresh

php artisan db:seed

php artisan route:clear

php artisan cache:clear

php artisan config:clear

php artisan config:cache

php artisan route:cache

php artisan view:cache

php artisan passport:client --personal
```

# Some other important commands

php artisan serve
