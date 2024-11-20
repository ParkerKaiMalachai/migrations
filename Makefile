DOCKER_COMPOSE = docker-compose.yaml

build:
	docker compose -f $(DOCKER_COMPOSE) up --build
start: 
	docker compose -f $(DOCKER_COMPOSE) up -d
down:
	docker compose -f $(DOCKER_COMPOSE) down
migrate_up:
	MIGRATE_ENV=up docker compose -f $(DOCKER_COMPOSE) up --build
migrate_down:
	MIGRATE_ENV=down docker compose -f $(DOCKER_COMPOSE) up --build