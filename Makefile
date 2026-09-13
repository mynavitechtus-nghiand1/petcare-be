.PHONY: help start stop restart shell logs migrate seed test docker-ps

.DEFAULT_GOAL := help

help: ## Show commands
	@echo "PetCare+ learning backend"
	@echo ""
	@echo "  make start     Start Docker + composer + migrate"
	@echo "  make stop      Stop containers"
	@echo "  make restart   Restart"
	@echo "  make shell     Bash into app container"
	@echo "  make logs      Follow logs"
	@echo "  make migrate   Run migrations"
	@echo "  make seed      Seed sample user"
	@echo "  make test      Run Pest tests"
	@echo "  make docker-ps Show containers"

start: ## Start learning environment
	@./scripts/dev.sh start

stop: ## Stop environment
	@./scripts/dev.sh stop

restart: ## Restart environment
	@./scripts/dev.sh restart

shell: ## App container shell
	@./scripts/dev.sh shell

logs: ## Follow logs
	@./scripts/dev.sh logs

migrate: ## Run migrations
	@./scripts/dev.sh migrate

seed: ## Seed database
	@./scripts/dev.sh seed

test: ## Run tests
	@./scripts/dev.sh test

docker-ps: ## List containers
	@docker compose ps
