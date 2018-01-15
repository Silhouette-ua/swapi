### Installation

In order to install application follow next steps:

- Clone project repository
- Change current directory to project folder
- Run ``composer install``
- Create .env file (see .env.example) and configure database connection
- Generate new application key with ``php artisan key:generate``
- To run migrations, execute ``php artisan migrate``
- To run built-in server, execute ``php artisan serve``

Application is ready for usage now.
If you want to test advanced functionality, you need to import data from SWAPI to database.
To do this, please execute ``php artisan data:import`` command

You are all set.
