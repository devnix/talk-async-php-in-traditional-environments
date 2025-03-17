install:
	composer install

rebuild:
	docker compose down --remove-orphans && docker compose build --pull --no-cache

start:
	docker compose up -d

stop:
	docker compose stop