build: up deps-install
up: docker-clear docker-build docker-up
down: docker-down

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