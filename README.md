#To install application(Linux machine, development environment):

###1. Build containers
- `make build_dev`

###2. Start containers
- `make start_dev`

###3. Install
- `make install`

##Windows(no makefile support):
###Open 'Makefile' and copy required commands

##Running tests:

###1. Build test environment:
- `make build_test`
- `make start_test`
- `make install`

###2. Go inside container
-`make execphp`

###3. Populate test database
- `php bin/console doctrine:fixtures:load`

###3. Run command:
- if it's your first time, you have to run it twice:
- `php bin/phpunit`