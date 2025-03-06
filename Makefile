# Variables
DOCKER = docker-compose
SYMFONY = symfony
COMPOSER = composer
PHP = php

# Colors
COLOR_RESET = \033[0m
COLOR_INFO = \033[32m
COLOR_COMMENT = \033[33m

# Main commands
.PHONY: help
help: ## Show this help
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: install
install: ## Install dependencies
	$(COMPOSER) install

.PHONY: test
test: ## Run tests
	$(PHP) bin/phpunit

.PHONY: migration
migration: ## Generate migration
	$(PHP) bin/console doctrine:migrations:diff

.PHONY: migrate
migrate: ## Run migrations
	$(PHP) bin/console doctrine:migrations:migrate --no-interaction

.PHONY: cache
cache: ## Clear cache
	$(PHP) bin/console cache:clear

.PHONY: start
start: ## Start application
	$(DOCKER) up -d

.PHONY: stop
stop: ## Stop application
	$(DOCKER) down

.PHONY: fixtures
fixtures: ## Load fixtures
	$(PHP) bin/console doctrine:fixtures:load --no-interaction

.PHONY: phpstan
phpstan: ## Run PHPStan
	$(PHP) vendor/bin/phpstan analyse

.PHONY: lint
lint: ## Run linting
	$(PHP) vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php
