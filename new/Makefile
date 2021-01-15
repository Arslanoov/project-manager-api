up: docker-clear docker-build docker-up composer-install validate
validate: check test
check: lint cs psalm
test: tests-run

docker-clear:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

composer-install:
	docker-compose run --rm furious-php-cli composer install

composer-update:
	docker-compose run --rm furious-php-cli compsoer update

generate-migration:
	docker-compose run --rm furious-php-cli php bin/console migrations:diff

migrate:
	docker-compose run --rm furious-php-cli php bin/console migrations:migrate

tests-run:
	docker-compose run --rm furious-php-cli php bin/phpunit

unit-tests-run:
	docker-compose run --rm furious-php-cli php bin/phpunit --testsuite=Unit

functional-tests-run:
	docker-compose run --rm furious-php-cli php bin/phpunit --testsuite=Functional

lint:
	docker-compose run --rm furious-php-cli composer lint

cs:
	docker-compose run --rm furious-php-cli composer cs-check

psalm:
	docker-compose run --rm furious-php-cli composer psalm
