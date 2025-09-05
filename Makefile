start: symfony-start composer-install mig

stop: symfony-stop

restart: stop start

symfony-start:
	@symfony server:start -d

symfony-stop:
	@symfony server:stop

clear-cache:
	@php bin/console cache:pool:clear cache.global_clearer --env=prod

mig:
	@php bin/console doc:mig:mig

composer-install:
	@composer install


mig-diff: clear-cache
	@php bin/console doc:mig:diff

test:
	@php bin/console --env=test doctrine:database:drop --force --if-exists
	@php bin/console --env=test doctrine:cache:clear-metadata
	@php bin/console --env=test doctrine:database:create
	@php bin/console --env=test  doctrine:schema:update --force
	@php bin/phpunit
	@php bin/console --env=test doctrine:database:drop --force

import-dump:
	@mysql -uroot -proot content < dump.sql


COMMIT_CHANGED_FILES := $(shell git diff --cached --name-only | grep -E ".php")
phpstan-current:
	@./vendor/bin/phpstan analyze $(COMMIT_CHANGED_FILES)
phpstan-all:
	@./vendor/bin/phpstan analyze

phpcs-current:
	@./vendor/bin/phpcs -p --standard=./ruleset.xml $(COMMIT_CHANGED_FILES)
phpcs-all:
	@./vendor/bin/phpcs -p --standard=./ruleset.xml

fix-current-dry:
	@export PHP_CS_FIXER_IGNORE_ENV=1 && ./vendor/bin/php-cs-fixer fix --config=./.php-cs-fixer.dist.php --using-cache=no -v --dry-run $(COMMIT_CHANGED_FILES)
fix-all-dry:
	@export PHP_CS_FIXER_IGNORE_ENV=1 && ./vendor/bin/php-cs-fixer fix --config=./.php-cs-fixer.dist.php --using-cache=no -v --dry-run

fix-current:
	@export PHP_CS_FIXER_IGNORE_ENV=1 && ./vendor/bin/php-cs-fixer fix --config=./.php-cs-fixer.dist.php --using-cache=no -v $(COMMIT_CHANGED_FILES)
fix-all:
	@export PHP_CS_FIXER_IGNORE_ENV=1 && ./vendor/bin/php-cs-fixer fix --config=./.php-cs-fixer.dist.php --using-cache=no -v

pre-commit:
	@php ./vendor/bin/grumphp run --testsuite=git_pre_commit


.PHONY: start stop restart symfony-start symfony-stop clear-cache mig composer-install phpcs-current phpcs-all fix-current-dry fix-all-dry fix-current fix-all pre-commit
