## Deployment

 1. Extract the archive and put it in the folder you want
 2. Run cp .env.example .env file to copy example file to .env
 3. Then edit your .env file with DB credentials and other settings.
 4. Run composer install command
 5. Run php artisan migrate --seed command.
 *Notice: seed is important, because it will create the first admin user for you.*
 
 6. Run php artisan key:generate command.
 7. Run php artisan storage:link command.

And that's it, go to your domain and login:


    Default credentials
    
    Username: admin@admin.com
    
    Password: password
