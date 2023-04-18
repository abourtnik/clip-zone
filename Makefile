.PHONY: help, exec, start, stop, optimize, deploy, install, test
.DEFAULT_GOAL=help

help: ## Show help options
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

exec: ## Enter in docker container
	docker exec -it php_container /bin/bash

reset: ## Reset database and run seeders
	docker exec -it php_container php artisan migrate:fresh --seed

start: ## Start dev server
	docker-compose up -d
	docker exec -it php_container npm run dev

stop: ## Stop dev server
	docker-compose down

optimize: ## Clear application cache
	docker exec -it php_container php artisan optimize

install: ## Install application
	php artisan migrate
	composer install --optimize-autoloader --no-dev
	npm install
	npm run build
	php artisan cache:clear

deploy: ## Deploy application
	ssh anton@51.178.29.115 -p 5789 -A 'cd /home/anton/www/clip-zone.com && git pull origin main && make install'

test: ## Run test
	docker exec -it php_container php artisan test
