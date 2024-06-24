## Information
- Please store all Model into the App\Models directory.
- All Model must extend BaseModel
- The routes in api.php, please create your own group for each module.
- running `php artisan db:seed --class=DefaultSeeder` to get default login username.<br>
username: admin@gmail.com | pass: admin


## Install & Setup
- Clone repository
- Get the packages by running `composer update`
- Copy .env.example content, paste it to the new file named .env
- Generate application key by running `php artisan key:generate`
- Create new database for local environment & edit the database configuration in .env
- Migrate & seed the database by running `php artisan migrate --seed`    
    
    



