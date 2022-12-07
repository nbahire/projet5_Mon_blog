# projet 5: Créez votre premier blog en PHP

## Install prerequisites

To run the docker commands without using **sudo** you must add the **docker** group to **your-user**:

```
sudo usermod -aG docker your-user
```

For now, this project has been mainly created for Unix `(Linux/MacOS)`. Perhaps it could work on Windows.

All requisites should be available for your distribution. The most important are :

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Check if `docker-compose` is already installed by entering the following command :

```sh
which docker-compose
```

Check Docker Compose compatibility :

* [Compose file version 3 reference](https://docs.docker.com/compose/compose-file/)

The following is optional but makes life more enjoyable :

```sh
which make
```

On Ubuntu and Debian these are available in the meta-package build-essential. On other distributions, you may need to install the GNU C++ compiler separately.

```sh
sudo apt install build-essential
```

### Images to use

* [Nginx](https://hub.docker.com/_/nginx/)
* [MySQL](https://hub.docker.com/_/mysql/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [traefik](https://hub.docker.com/_/traefik/)
* [Composer](https://hub.docker.com/_/composer/)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
* [Generate Certificate](https://hub.docker.com/r/jacoelho/generate-certificate/)

You should be careful when installing third party web servers such as MySQL or Nginx.

This project use the following ports :

| Server     | Port |
|------------|------|
| MySQL      | 8989 |
___
1. Generate SSL certificates

    ```sh
    source .env && docker run --rm -v $(pwd)/etc/ssl:/certificates -e "SERVER=$NGINX_HOST" jacoelho/generate-certificate
    ```
___

## Run the application

1. run the composer :

    ```sh
    cd web &&  composer install --dev
    ```

2. Start the application :

    ```sh
    docker compose up -d
    ```

   **Please wait this might take a several minutes...**

    ```sh
    docker compose logs -f # Follow log output
    ```

3. Open your favorite browser :

    * [https://monitor.localhost](http://monitor.localhost) Traefik monitor
    * [https://localhost](https://localhost) App
    * [https://pma.localhost](http://pma.localhost) PHPMyAdmin (username: dev, password: dev)

4. Stop and clear services

    ```sh
    docker compose down -v
    ```

___

Based on [ https://github.com/nanoninja/docker-nginx-php-mysql](https://github.com/nanoninja/docker-nginx-php-mysql)
