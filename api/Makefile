help:                                                                           ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: init
init:                                                                           ## initialize or update project
	composer install
#	make reset-database
#	make jwt-create-keys

.PHONY: rebuild
rebuild:                                                                        ## rebuild project
	composer install
	make reset-database

.PHONY: reset-database
reset-database:                                                                 ## rebuild database
	make destroy-database
	make create-database
	make load-fixtures

.PHONY: create-database
create-database:                                                                ## create database
	bin/console doctrine:database:create --if-not-exists
	bin/console doctrine:migration:migrate --no-interaction
	bin/console doctrine:schema:validate

.PHONY: load-fixtures
load-fixtures:                                                                  ## load fixtures
	#bin/console app:test:data

.PHONY: destroy-database
destroy-database:                                                               ## destroy database
	bin/console doctrine:database:drop --if-exists --force

.PHONY: logs
logs:                                                                           ## display logs
	tail -f var/log/dev.log

.PHONY: cache
cache:                                                                          ## remove and warmup cache
	bin/console cache:clear --no-warmup
	bin/console cache:warmup

.PHONY: php-cs-check
php-cs-check:                                                                   ## run cs fixer (dry-run)
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes --diff --dry-run

.PHONY: php-cs-fix
php-cs-fix:                                                                     ## run cs fixer
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes

.PHONY: phpstan
phpstan:                                                                        ## run phpstan static code analyser
	phpstan analyse -l max -c phpstan.neon src

.PHONY: psalm
psalm:                                                                          ## run psalm static code analyser
	psalm

.PHONY: check-dependencies
check-dependencies:																## run composer require checker
	require-checker check ./composer.json

.PHONY: deptrac
deptrac:																		## run deptrac
	deptrac analyze depfile.yml
	deptrac analyze depfile_domain.yml

.PHONY: composer-normalize
composer-normalize:															    ## run composer-normalize
	composer-normalize

.PHONY: composer-normalize-check
composer-normalize-check:													    ## run composer-normalize as dry run
	composer-normalize --dry-run

.PHONY: security
security:                                                                       ## run security checker
	security-checker security:check

.PHONY: lint
lint:                                                                           ## lint container, twig and yaml
	#bin/console lint:container
	bin/console lint:twig templates
	bin/console lint:yaml config --parse-tags

.PHONY: phpunit
phpunit:                                                                        ## run phpunit tests
	php vendor/bin/phpunit --testdox --colors=always -v $(OPTIONS)

.PHONY: snapshots
snapshots:                                                                      ## update snapshots
	php vendor/bin/phpunit --testdox --colors=always -v -d --update-snapshots $(OPTIONS)

.PHONY: static
static: php-cs-check phpstan psalm deptrac check-dependencies lint security composer-normalize-check    ## run static analyser

.PHONY: test
test: phpunit                                                                   ## run tests

.PHONY: dev
dev: static test                                                                ## run dev tools

.PHONY: jwt-create-keys
jwt-create-keys:                                                                ## create private and public keys
	openssl genrsa -passout pass:1234 -out config/jwt/private.pem -aes256 4096
	openssl rsa -passin pass:1234 -pubout -in config/jwt/private.pem -out config/jwt/public.pem

.PHONY: worker-run-done
worker-run-done:                                                                ## start worker for done queue
	bin/console messenger:consume -vv async_assignment_done

.PHONY: worker-run-todo
worker-run-todo:                                                                ## start worker for todo queue
	bin/console messenger:consume -vv async_assignment_todo

.PHONY: mysql
mysql:                                                                          ## go in mysql
	sudo docker exec -it mysql /usr/bin/mysql app
