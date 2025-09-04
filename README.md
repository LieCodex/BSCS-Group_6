# RizGroup

To make this project work, do this step

1. clone the project using git clone command in the htdocs folder in xampp program files if you are using xampp or in the www if you are using laragon
    "git clone <url>"
    then "cd RizGroup" => "cd app"
2. run "composer install" in your terminal
3. run this command "cp .env.example .env "
4. the this "php artisan key:generate"
5. then this "php artisan migrate"
6. then this "npm install
npm run dev"
7. Crtl + C to stop the process
9. Since we are using images, run "php artisan storage:link", if you aren't, then don't run this
8. then run "php artisan serve"

When pulling new data run these commands to make sure nothing will go wrong with syncronazation

1. "composer install" - if there is changes in composer.json
2. "npm install" - if there is changes in package.json
3. "php artisan migrate" - if there is data changes in database

If by some situation where php does not work, follow these steps:
There maybe be 2 php versions being used, one in xampp and one in your system.
The one that is working is the one in xampp, so you need to change the php path in your system environment variable.
To do this, follow these steps:
1. Search for "Environment Variables" in your system settings.
2. Click on "Environment Variables".
3. Under "System variables", find the "Path" variable and click "Edit".
4. Add the path to the php executable in your xampp installation, typically something like `C:\xampp\php`., if it's on laragon it will be `C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64`
   - Make sure to use the correct version of PHP that your project requires.
5. move the new path to the top of the list to give it priority above other php installations.
6. Click "OK" to save the changes.
7. Restart your terminal or command prompt and vsCode to apply the changes.

Possible Problem: cURL Error 60  SSL Certificate Issue

Run:
php --ini

It should show:
Loaded Configuration File: C:\xampp\php\php.ini

If it doesn't, go to the path shown and open the php.ini file manually.
Download this file: https://curl.se/ca/cacert.pem
Save it to:
C:\xampp\php\extras\SSL

Copy the full path of the file:
C:\xampp\php\extras\SSL\cacert.pem

Open php.ini and search for curl.cainfo and openssl.cafile.
Remove any semicolons (;) at the start of the lines.

Paste the path so it looks like this:

curl.cainfo="C:\xampp\php\extras\SSL\cacert.pem"
openssl.cafile="C:\xampp\php\extras\SSL\cacert.pem"

Save the php.ini file.

Restart the Apache server in XAMPP.


