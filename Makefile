up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up parser-init
test: parser-test

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

parser-init: parser-composer-install parser-migrations

parser-composer-install:
	docker-compose run -u $(id -u ${USER}):$(id -g ${USER}) --rm parser-php-cli composer install

parser-test:
	docker-compose run -u $(id -u ${USER}):$(id -g ${USER}) --rm parser-php-cli php bin/phpunit

parser-migrations:
	docker-compose run -u $(id -u ${USER}):$(id -g ${USER}) --rm parser-php-cli php bin/console doctrine:migrations:migrate --no-interaction

parse:
	docker-compose run -u $(id -u ${USER}):$(id -g ${USER}) --rm parser-php-cli php bin/console parser:parse
