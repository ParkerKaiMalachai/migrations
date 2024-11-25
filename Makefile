DOCKER_COMPOSE = docker-compose.yaml

build:
	docker compose -f $(DOCKER_COMPOSE) up --build -d
start: 
	docker compose -f $(DOCKER_COMPOSE) up -d
down:
	docker compose -f $(DOCKER_COMPOSE) down

migrate_up:
	docker exec -it migrations-php-1 php cli/execute.php --direction=up
migrate_down:
	docker exec -it migrations-php-1 php cli/execute.php --direction=down