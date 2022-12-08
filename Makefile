
up:
	docker compose up -d

down:
	docker compose down

composer:
	cd web && composer install

cs-fixer:
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix app/src
