install:
	composer install
	npm i
	npm run prod

lint:
	composer run-script phpcs -- --standard=PSR2 app database resources routes storage tests

fix:
	composer run-script phpcbf -- --standard=PSR2 app database resources routes storage tests

test:
	composer run-script phpunit

t:
	php artisan dusk

w:
	npm run w

wd:
	npm run wd

run:
	php -S localhost:80 -t public

s:
	php artisan serve

m:
	php artisan migrate

mf:
	php artisan migrate:fresh

seed:
	php artisan db:seed

fseed:
	php artisan migrate:fresh --seed

logs:
	tail -f storage/logs/lumen.log

load:
	composer dump-autoload
