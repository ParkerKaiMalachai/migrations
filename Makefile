DOCKER_COMPOSE = docker-compose.yaml

build:
	docker compose -f $(DOCKER_COMPOSE) up --build
start: 
	docker compose -f $(DOCKER_COMPOSE) up -d
down:
	docker compose -f $(DOCKER_COMPOSE) down