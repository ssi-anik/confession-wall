# Confession wall

An implementation of APIs and GraphQL with Lumen for anonymous confession. Not a serious project. Check the articles if you're here to implement the GraphQL with Laravel/Lumen.

## Lumen Version
This repository contains Lumen `8.x` and Lighthouse `5.2.x`

## Dependencies, requirements and build tools
This project comes with `docker` & `docker-compose`. But to minimize the boot up time when you try `docker-compose up -d --build` the local files are mounted to application & worker containers and **NOT COPIED TO CONTAINERS**.
Thus, it's recommended to use PHP & composer locally. Resolve your project dependency before you run your application using `composer install`.

This project contains,
- `nginx` for web server.
- `postgres` for database.
- `redis` for cache.
- `beanstalkd` for queue driver.
- `beanstalk-console` as beanstalk's admin tool.

## How to use?
- Clone the repository.
- `cp docker-compose.yml.example docker-compose.yml`.
- Make the required changes to your `docker-compose.yml`.
- `cp .env.example .env`.
- Make the required changes to your `.env`.
- `docker-compose up -d --build` to build your containers.
- Run `php artisan key:generate` to generate application key.
- Loading `http://127.0.0.1:{NGINX_PORT}` in your browser will return a json response.
- If you don't have `composer` locally, then `exec`-ing to php container after containers are up and install the dependencies will work. Just restart the containers.
- If you're changing the environment variables, make sure to change them other places and run commands if required.
