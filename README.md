# RosterBotsAPI

## Installation

- Install Composer and PHP
- Clone the project
- Go to the project folder
- Run composer install

## Running

- Go to the project folder
- Run php bin/console server:run 127.0.0.1:8000

## Running tests

- Go to the project folder
- Run ./vendor/bin/phpunit

## Database
Opion 1: AWS

In order to facilitate testing, the database is hosted in a MySQL instance on AWS RDS. 
Make sure you have an internet connection. You might have problems because of your firewall restrictions.

Option 2: Local

In case you want or need to use a local database, follow these instructions:
- Copy app/config/parameters.dev.yml to app/config/parameters.yml
- Create a Database named RosterBots and other named RosterBots
- Create a user 'rosterbots' with password 'rosterbots'
- Grant all privileges on RosterBots.* and RosterBotsTest.* to the created user
- Run php bin/console doctrine:schema:update --force
- Run php bin/console doctrine:schema:update --force --env=test
