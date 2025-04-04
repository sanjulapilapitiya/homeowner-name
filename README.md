# homeowner-names
<h1>setup<h1>

cd into homeOwner-api and run docker compose run --rm app bash
then run npm install then composer install

cd into homeOwner-ui and run docker compose run --rm app bash
then run npm install

next go back to the homeowner-names dir, run docker compose build then docker compose up

now http://localhost:5173/ should be exposed and when visited should see a homeOwner Import, Choose the homeOwner CSV file as the import and see the api response being returned. 

to run the php unit test exec into laravel_app and run the command: php artisan test