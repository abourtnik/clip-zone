.PHONY: help, exec, start, stop, optimize
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
