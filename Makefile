build: up deps-install test generate-keys set-permissions validate
up: docker-clear docker-build docker-up
down: docker-down
validate: check test
test: test-unit test-functional
check: lint cs psalm
generate-keys: generate-private-key generate-public-key
set-permissions: set-project-permissions set-keys-permissions

docker-up:
	docker-compose up -d
docker-down:
	docker-compose down
docker-build:
	docker-compose build
docker-clear:
	docker-compose down --remove-orphans

deps-install:
	docker-compose run --rm api-php-cli composer install
deps-update:
	docker-compose run --rm api-php-cli composer update
dump-autoload:
	docker-compose run --rm api-php-cli composer dump-autoload

test-unit:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --testsuite=Unit
test-functional:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --testsuite=Functional

generate-private-key:
	docker-compose run --rm api-php-cli openssl genrsa -out private.key 2048
generate-public-key:
	docker-compose run --rm api-php-cli openssl rsa -in private.key -pubout -out public.key

migrate:
	docker-compose run --rm api-php-cli php bin/console migrations:migrate

set-project-permissions:
	docker-compose run --rm api-php-cli chmod -R 777 var
set-keys-permissions:
	docker-compose run --rm api-php-cli chmod 755 public.key
	docker-compose run --rm api-php-cli chmod 755 private.key

lint:
	docker-compose run --rm api-php-cli composer lint
cs:
	docker-compose run --rm api-php-cli composer cs-check
psalm:
	docker-compose run --rm api-php-cli composer psalm
