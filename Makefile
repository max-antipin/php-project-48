install:
	composer install

lint:
	composer validate --strict && \
	composer exec --verbose phpcs -- --standard=PSR12 src tests bin/* && \
	composer exec --verbose phpstan analyze

lint-fix:
	composer exec --verbose phpcbf -- src tests bin/*

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover=build/logs/clover.xml

fix-format:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin/*
