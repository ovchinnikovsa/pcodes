up:
	docker-compose up -d
upb:
	docker-compose up -d --build
down:
	docker-compose down
execp:
	docker-compose exec php bash
execn:
	docker-compose exec nginx bash
