PROJECT_DIR=.
DOCKER_DIR=$(PROJECT_DIR)/.docker
DOCKER_COMPOSE_FILE=$(PROJECT_DIR)/docker-compose.yml
DOCKER_COMPOSE_FILE_DEV=$(PROJECT_DIR)/docker-compose-dev.yml
DOCKER_COMPOSE=docker-compose -f $(DOCKER_COMPOSE_FILE)
DOCKER_COMPOSE_DEV=docker-compose -f $(DOCKER_COMPOSE_FILE) -f $(DOCKER_COMPOSE_FILE_DEV)

APP_USER_ID := $(shell id -u)
APP_GROUP_ID := $(shell id -g)
BUILD_ARGS=--build-arg APP_USER_ID=${APP_USER_ID} --build-arg APP_GROUP_ID=${APP_GROUP_ID}

#COLORS
GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RED    := $(shell tput -Txterm setaf 1)
RESET  := $(shell tput -Txterm sgr0)
# Add the following 'help' target to your Makefile
# And add help text after each target name starting with '\#\#'
# A category can be added with @category
HELP_FUN = \
    %help; \
    while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
    print "usage: make [target]\n\n"; \
    for (sort keys %help) { \
    print "${WHITE}$$_:${RESET}\n"; \
    for (@{$$help{$$_}}) { \
    $$sep = " " x (32 - length $$_->[0]); \
    print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
    }; \
    print "\n"; }



.DEFAULT_GOAL := help

help: ##@other Show this help.
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)

.PHONY: install
install: ## Install development environment
	$(DOCKER_COMPOSE_DEV) down
	$(DOCKER_COMPOSE_DEV) build $(BUILD_ARGS)


.PHONY: dev
dev: ## Run app in development environment
	$(DOCKER_COMPOSE_DEV) down
	$(DOCKER_COMPOSE_DEV) build $(BUILD_ARGS)
	$(DOCKER_COMPOSE_DEV) run --user=www-data client composer install --ignore-platform-reqs
	$(DOCKER_COMPOSE_DEV) build $(BUILD_ARGS) server
	$(DOCKER_COMPOSE_DEV) up -d
	$(DOCKER_COMPOSE_DEV) exec --user=www-data server composer install --ignore-platform-reqs


.PHONY: stop
stop: ## Stop the application
	$(DOCKER_COMPOSE_DEV) down


.PHONY: test
test: ## Run app in development environment
	$(DOCKER_COMPOSE_DEV) run --user=www-data client bin/phpunit
	$(DOCKER_COMPOSE_DEV) exec --user=www-data server vendor/bin/phpunit


.PHONY: shell-server
shell-server: ## Run shell in dev container
	$(DOCKER_COMPOSE_DEV) run server /bin/sh


.PHONY: shell-client
shell-client: ## Run shell in dev container
	$(DOCKER_COMPOSE_DEV) run client /bin/sh


.PHONY: prod
prod: ## Run app in prod environment
	$(DOCKER_COMPOSE) build $(BUILD_ARGS)


.PHONY: run
run:
	$(DOCKER_COMPOSE) run --user=www-data client
	$(DOCKER_COMPOSE) up -d
