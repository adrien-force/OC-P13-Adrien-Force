# Variables
DOCKER = docker-compose
SYMFONY = symfony
COMPOSER = composer
PHP = php
DOCKER_EXEC = docker exec
DOCKER_PHP = $(DOCKER_EXEC) OC-P13-php php

# Colors
COLOR_RESET = \033[0m
COLOR_INFO = \033[32m
COLOR_COMMENT = \033[33m

# Main commands
.PHONY: help
help: ## Show this help
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: ## Install project
	$(MAKE) start
	$(MAKE) composer
	$(MAKE) jwt
	$(MAKE) migrate
	$(MAKE) fixtures

reinstall: ## Reinstall project completely
	$(DOCKER) down --volumes --remove-orphans
	$(DOCKER) up -d --build
	$(MAKE) install

.PHONY: composer
composer: ## Install dependencies
	$(COMPOSER) install

.PHONY: test
test: ## Run tests
	$(DOCKER_PHP) bin/phpunit

.PHONY: migration
migration: ## Generate migration
	$(DOCKER_PHP) bin/console doctrine:migrations:diff

.PHONY: migrate
migrate: ## Run migrations
	$(DOCKER_PHP) bin/console doctrine:migrations:migrate --no-interaction

.PHONY: cache
cache: ## Clear cache
	$(DOCKER_PHP) bin/console cache:clear

.PHONY: start
start: ## Start application
	$(DOCKER) up -d

.PHONY: stop
stop: ## Stop application
	$(DOCKER) down

.PHONY: fixtures
fixtures: ## Load fixtures
	$(DOCKER_PHP) bin/console doctrine:fixtures:load --no-interaction

.PHONY: phpstan
phpstan: ## Run PHPStan
	$(DOCKER_PHP) vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: lint
lint: ## Run linting
	$(DOCKER_PHP) vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php

.PHONY: jwt
jwt: ## Generate JWT
	$(DOCKER_PHP) bin/console lexik:jwt:generate-keypair --skip-if-exists
