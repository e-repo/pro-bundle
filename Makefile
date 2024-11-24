UID=1000
DOCKER_ARGS=--log-level=ERROR

# common services
TRAEFIK=traefik

# be services
BE-POSTGRES=postgres
BE-NGINX=nginx
BE-FPM=php-fpm
BE-CLI=php-cli
BE-MAILER=mailer
BE-MINIO=minio


# fe admin services
ADMIN_NODE=node
ADMIN_NGINX=admin-nginx


# init apps
be-init: docker-down be-docker-pull be-docker-build be-docker-up be-post-install
amin-init: docker-down admin-docker-pull admin-init admin-post-install


# common command
down: docker-down
ps: docker-ps
#restart: down up
#test: bb-test


# backend command
b-up: be-docker-up
b-shell:
	@docker-compose run --rm $(BE-CLI) sh


# admin command
admin-up: admin-docker-up
admin-shell:
	@docker exec -it $(ADMIN_NODE) sh
admin-serve:
	@docker exec -it $(ADMIN_NODE) yarn dev
admin-lint:
	@docker exec -it $(ADMIN_NODE) yarn lint


be-docker-up:
	docker-compose up -d -- $(TRAEFIK) $(BE-POSTGRES) $(BE-MINIO) $(BE-FPM) $(BE-CLI) $(BE-NGINX) $(BE-MAILER)

admin-docker-up:
	docker-compose up -d -- $(TRAEFIK) $(ADMIN_NGINX) $(ADMIN_NODE)

docker-ps:
	@docker-compose ps

docker-down:
	docker-compose down --remove-orphans

#docker-down-clear:
#	docker-compose down -v --remove-orphans

be-docker-pull:
	docker-compose pull -- $(TRAEFIK) $(BE-POSTGRES) $(BE-MINIO) $(BE-FPM) $(BE-CLI) $(BE-NGINX)

admin-docker-pull:
	docker-compose pull -- $(TRAEFIK) $(ADMIN_NGINX) $(ADMIN_NODE)

be-docker-build:
	docker-compose build -- $(TRAEFIK) $(BE-POSTGRES) $(BE-MINIO) $(BE-FPM) $(BE-CLI) $(BE-NGINX)

admin-init:
	@docker-compose run --rm $(ADMIN_NODE) yarn install

bb-composer-install:
	@docker-compose run --rm $(BE-CLI) composer install

bb-wait-db:
	until docker-compose exec -T $(BE-POSTGRES) pg_isready --timeout=0 --dbname=shop ; do sleep 1 ; done

be-post-install: bb-composer-install bb-wait-db bb-migrations b-chown

admin-post-install: admin-chown

bb-migrations:
	@docker-compose run --rm $(BE-CLI) php bin/console do:mi:mi --no-interaction

bb-test:
	@docker-compose run --rm $(BE-CLI) php bin/phpunit

admin-chown:
	@docker exec $(ADMIN_NODE) chown -R $(UID):$(UID) ./

b-chown:
	@docker exec $(BE-NGINX) chown -R $(UID):$(UID) ./
