UID=1000
DOCKER_ARGS=--log-level=ERROR

NGINX_SERVICE=bb-nginx
CLI_SERVICE=bb-cli
FPM_SERVICE=bb-fpm
NODE_ADMIN_SERVICE=fb-admin-node

be-init: docker-down docker-pull docker-build docker-up bb-init be-post-install fe-init fe-post-install

up: docker-up

down: docker-down
restart: down up
test: bb-test
ps: docker-ps

admin-serve:
	@docker exec -it fb-admin-node yarn dev

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

fe-init: fe-admin-init

fe-admin-init:
	@docker-compose run --rm $(NODE_ADMIN_SERVICE) yarn install

bb-composer-install:
	@docker-compose run --rm $(CLI_SERVICE) composer install

bb-wait-db:
	until docker-compose exec -T bb-postgres pg_isready --timeout=0 --dbname=shop ; do sleep 1 ; done

be-post-install: b-chown

fe-post-install: admin-chown

bb-migrations:
	@docker-compose run --rm $(CLI_SERVICE) php bin/console do:mi:mi --no-interaction

bb-test:
	@docker-compose run --rm $(CLI_SERVICE) php bin/phpunit

admin-chown:
	@docker exec $(NODE_ADMIN_SERVICE) chown -R $(UID):$(UID) ./

b-chown:
	@docker exec $(NGINX_SERVICE) chown -R $(UID):$(UID) ./