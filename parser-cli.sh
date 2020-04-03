#!/bin/bash
docker-compose run -u $(id -u ${USER}):$(id -g ${USER}) --rm parser-php-cli /bin/bash
