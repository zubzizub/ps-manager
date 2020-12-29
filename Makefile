up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear clear docker-pull docker-build docker-up project-init
test: project-test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

clear:
	docker run --rm -v ${PWD}/project:/app --workdir=/app alpine rm -f .ready

project-init: composer-install assets-install wait-db migrations ready

composer-install:
	docker-compose run --rm php-cli composer install

assets-install:
	docker-compose run --rm node yarn install

wait-db:
	until docker-compose exec -T postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

ready:
	docker run --rm -v ${PWD}/project:/app --workdir=/app alpine touch .ready

permission:
	docker-compose run --rm php-fpm chown -R 1000:www-data .

php-in:
	docker-compose exec php-fpm bash

cache-clear:
	docker-compose run --rm php-cli php bin/console cache:clear

project-test:
	docker-compose run --rm php-cli php bin/phpunit

migrations:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction

migrations-diff:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:diff --no-interaction

migrations-validate:
	docker-compose run --rm php-cli php bin/console doctrine:schema:validate

assets-dev:
	docker-compose run --rm node npm run dev
