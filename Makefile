UID=1000
DOCKER_ARGS=--log-level=ERROR
NGINX_SERVICE=bb-nginx
CLI_SERVICE=bb-cli
FPM_SERVICE=bb-fpm

init: docker-down docker-pull docker-build docker-up bb-init post-install

up: docker-up
down: docker-down
restart: down up
test: bb-test
ps: docker-ps

b-shell:
	@docker exec -it bb-fpm bash

cli-shell:
	@docker exec -it bb-cli bash

docker-up:
	docker-compose up -d

docker-ps:
	@docker-compose ps

docker-down:
	docker-compose down --remove-orphans

#docker-down-clear:
#	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

bb-init: bb-composer-install bb-wait-db bb-migrations

bb-composer-install:
	@docker-compose run --rm $(CLI_SERVICE) composer install

bb-assets-dev:
	docker-compose run --rm bb-node npm run dev

bb-wait-db:
	until docker-compose exec -T bb-postgres pg_isready --timeout=0 --dbname=shop ; do sleep 1 ; done

post-install: b-chown

bb-migrations:
	@docker-compose run --rm $(CLI_SERVICE) php bin/console do:mi:mi --no-interaction

bb-test:
	@docker-compose run --rm $(CLI_SERVICE) php bin/phpunit

f-chown:
	@docker exec ft-node chown -R $(UID):$(UID) ./

b-chown:
	@docker-compose $(DOCKER_ARGS) exec $(NGINX_SERVICE) chown -R $(UID):$(UID) ./