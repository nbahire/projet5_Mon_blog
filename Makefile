
up:
	docker compose up -d

down:
	docker compose down

composer:
	cd web && composer install && cd -

cs-fixer:
	cd web/tools/php-cs-fixer && composer i && cd - && web/tools/php-cs-fixer/vendor/bin/php-cs-fixer fix web/app/

