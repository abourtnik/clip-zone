.PHONY: help, exec, start, stop, optimize, deploy, install, test, logs, init, stripe, analyse, helpers
.DEFAULT_GOAL=help

help: ## Show help options
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

connect: ## Enter in docker container
	docker exec -it php_container /bin/bash

reset: ## Reset database and run seeders
	docker exec -it php_container php artisan migrate:fresh --seed

start: ## Start dev server
	docker-compose -p clipzone up -d

stop: ## Stop dev server
	docker-compose -p clipzone down

optimize: ## Clear application cache
	docker exec -it php_container php artisan optimize

install: ## Install application
	composer install --optimize-autoloader --no-dev
	php artisan migrate --force
	php artisan queue:restart
	php artisan scout:sync-index-settings
	npm install
	npm run build
	php artisan optimize
	php artisan cache:clear

init: ## Init application
	cp .env.example .env
	docker-compose up -d
	docker exec -it php_container npm install
	docker exec -it php_container composer install
	docker exec -it php_container php artisan key:generate
	docker exec -it php_container php artisan storage:link
	docker exec -it php_container php artisan db:create
	docker exec -it php_container php artisan migrate
	docker exec -it php_container php artisan optimize
	docker exec -it php_container php artisan cache:clear
	docker exec -it php_container npm run dev

deploy: ## Deploy application
	git pull origin main
	make install

test: ## Run test
	docker exec -it php_container php artisan config:clear
	docker exec -it php_container php artisan test --stop-on-failure --env=testing

logs: ## See last logs
	docker exec -it php_container tail -f storage/logs/laravel.log

stripe: ## See Stripe Webhook logs
	docker logs -f stripe_container

restart-horizon: ## Restart Horizon
	docker-compose restart queues_container

analyse: ## Execute Larastan
	docker exec -it php_container ./vendor/bin/phpstan analyse --memory-limit=2G

helpers: ## Generate Helpers
	docker exec -it php_container php artisan ide-helper:generate
	docker exec -it php_container php artisan ide-helper:models -F helpers/ModelHelper.php -M
	docker exec -it php_container php artisan ide-helper:meta
