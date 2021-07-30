#!/usr/bin/env sh

docker run --rm --interactive --tty \
  --volume $PWD/php-app/src:/app \
  --user $(id -u):$(id -g) \
  composer install

docker run --rm -it --volume $PWD/react-app:/app \
  -w /app \
  --user $(id -u):$(id -g) \
  node yarn install

docker-compose up
docker-compose exec backend chown -R www-data:www-data ./storage
