.PHONY: help, connect, bun, reset, start, stop, optimize, update, install, deploy, test, logs, stripe, restart-horizon, analyse, helpers
.DEFAULT_GOAL=help

help: ## Show help options
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

connect: ## Enter in php container
	docker exec -it php_container /bin/bash

bun: ## Enter in bun container
	docker exec -it bun_container /bin/sh

reset: ## Reset database and run seeders
	docker exec -it php_container php artisan migrate:fresh --seed

start: ## Start dev server
	docker compose -p clipzone up -d

stop: ## Stop dev server
	docker compose -p clipzone down

optimize: ## Clear application cache
	docker exec -it php_container php artisan optimize

update: ## Update application
	composer install --optimize-autoloader --no-dev
	php artisan migrate --force
	php artisan queue:restart
	php artisan scout:sync-index-settings
	/home/anton/.bun/bin/bun install
	/home/anton/.bun/bin/bun run build
	php artisan optimize
	php artisan cache:clear

install: ## Install application
	cp .env.example .env
	docker compose up php mariadb bun redis meilisearch minio -d
	docker exec -it php_container composer install
	docker exec -it php_container php artisan key:generate
	docker exec -it php_container php artisan db:create
	docker exec -it php_container php artisan db:create clipzone_test
	docker exec -it php_container php artisan migrate
	docker exec -it php_container php artisan db:seed
	docker exec -it php_container php artisan scout:sync-index-settings
	docker exec -it php_container php artisan log-viewer:publish
	docker exec -it php_container mkdir -p storage/app/videos storage/app/spams storage/app/thumbnails
	docker exec -it minio_container mc alias set dockerminio http://minio:9000 minio password
	docker exec -it minio_container mc mb dockerminio/clipzone
	docker exec -it minio_container mc admin accesskey create dockerminio minio --access-key minio-id --secret-key minio-secret
	docker exec -it minio_container mc anonymous set-json /home/policy.json dockerminio/clipzone
	make helpers
	docker exec -it php_container php artisan optimize
	docker exec -it php_container php artisan cache:clear
	docker compose down
	make start

deploy: ## Deploy application
	git pull origin main
	make update

test: ## Run PHP test
	docker exec -it php_container php artisan config:clear
	docker exec -it php_container php artisan test --env=testing --stop-on-failure $(if $(FILTER),--filter $(FILTER),)

logs: ## See last logs
	docker exec -it php_container tail -f storage/logs/laravel.log

stripe: ## See Stripe Webhook logs
	docker logs -f stripe_container

restart-horizon: ## Restart Horizon
	docker restart queues_container

analyse: ## Execute Larastan
	docker exec -it php_container ./vendor/bin/phpstan analyse --memory-limit=2G

helpers: ## Generate Laravel Helpers
	docker exec -it php_container php artisan ide-helper:generate
	docker exec -it php_container php artisan ide-helper:models -F helpers/ModelHelper.php -M
	docker exec -it php_container php artisan ide-helper:meta
