# RizGroup

To make this project work, do this step

1. clone the project using git clone command
2. run "composer install" in your terminal
3. run this command "cp .env.example .env "
4. the this "php artisan key:generate"
5. then this "php artisan migrate"
6. then this "npm install
npm run dev"
7. Crtl + C to stop the process
8. then run "php artisan serve"

When pulling new data run these commands to make sure nothing will go wrong with syncronazation

1. "composer install" - if there is changes in composer.json
2. "npm install" - if there is changes in package.json
3. "php artisan migrate" - if there is data changes in database
