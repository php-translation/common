.PHONY: ${TARGETS}

DIR := ${CURDIR}
QA_IMAGE := jakzal/phpqa:php7.3-alpine

cs-fix:
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff -vvv

cs-diff:
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff --dry-run -vvv

phpstan:
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) phpstan analyze

phpunit:
	@vendor/bin/phpunit

static: cs-diff phpstan

test: static phpunit
