## Set up

1. Run `docker up --build -d`
2. In docker container -> web, run
    - `php artisan migrate && php artisan db:seed`
    - `php artisan scout:import "App\Models\Listing"`
