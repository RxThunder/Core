cs:
	vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v

cs_dry_run:
	vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run

analyse:
	vendor/bin/phpstan analyse -l 7 src
