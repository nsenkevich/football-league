# Football-league
Football league

##Api Folder structure
1. Domain - domain level (aggregate root, entities, value objects).
2. Aplication - application level (request response handling, data formating).
3. Infrastructure - infrastructure level (doctrine/sql specific implementation).

for more info see http://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html

- to load dependencies composer update

- to run php -S 127.0.0.1:8000 -t public

- for phpunit vendor/bin/phpunit

- for postman tests use Championship.postman_collection
