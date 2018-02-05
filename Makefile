install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR2 app database resources routes storage tests

fix:
	composer run-script phpcbf -- --standard=PSR2 app database resources routes storage tests

test:
	composer run-script phpunit

run:
	php -S localhost:80 -t public

logs:
	tail -f storage/logs/lumen.log
