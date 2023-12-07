up:
	make prepare
	docker compose up -d --build --remove-orphans
	docker compose exec php composer install
	echo "Service is ready"

prepare:
	docker network create currencyConverter || echo "Network exists, it's ok"
	test -f .env || cp .env.example .env
	test -f docker-compose.yml || ln -s docker-compose.local.yml docker-compose.yml

test:
	docker compose exec php php artisan test
