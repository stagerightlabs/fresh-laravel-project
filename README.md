# How I Start a Laravel Project

2025 Edition

This is my own preferred way to start a fresh Laravel project. It leverages some recommended Laravel defaults such as Vite and Laravel Pint, but it also deviates from the defaults in ways that I prefer, such as using my own docker setup instead of Laravel Sail.

## Stack

- PHP 8.4
- PostgreSQL
- Docker

## Features

- Vite for asset management
- Yarn for node dependency management
- Larastan and PHPStan for static analysis
- Laravel Pint for PHP code formatting
- Prettier for JavaScript and Blade code formatting
- MailPit for email previews and testing

## Getting Started

You can use this repository as a starting point for a new project:

1. Clone the repository into a new project folder.
2. Remove the existing `.git` file and run `git init` to start with a fresh git history.
3. Run `docker compose build` to build the local environment images.
4. Install and update the PHP dependencies: `docker compose run --rm php composer update`.
5. Install the node dependencies: `docker compose run --rm node yarn`.
6. Optional: Add an entry to your hosts file for the local project domain.
7. Spin up the containers: `docker compose up -d`.
8. Run the database migrations: `docker compose exec php php artisan migrate`

You should now be able to view the project in a web browser.

To view mail go to [localhost:8025](http://localhost:8025) when the containers are running.

## Helper Script

This project includes an `ops.sh` script that serves as a helper for accessing tools in docker. This is an idea that I borrowed from Chris Fidao and his [Shipping Docker](https://serversforhackers.com/shipping-docker) course. To make best use of it, you will need to set up a bash alias:

```sh
app() {
    cd /full/path/to/project
    ./ops.sh ${*:-ps}
    cd $OLDPWD
}
```

You can call the function whatever you would like. With this alias in place you can now easily run development tasks:

```sh
project $ app artisan make:model Team -mfs # Make a new model with a migration, factory and seeder
project $ app composer require [package] # Add a PHP package with Composer
project $ app composer format # Run Laravel Pint to format PHP code
project $ app composer phpstan # Run PHPStan for static analysis
project $ app composer test # Run the test suite
project $ app psql app_dev # Drop into the postgres cli
project $ app yarn add [package] # Add a node dependency with Yarn
project $ app yarn format # Run a yarn script
```

This script is easily extendible if you add more services to the docker environment.
