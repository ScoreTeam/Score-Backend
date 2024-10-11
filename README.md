# Notice :

this version is very outdated and does not work with the actual project

if you are a former member of the team and by any chance you have read this message and you want the latest version of this repo please mail me at hamzamuhareb777@gmail.com

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
