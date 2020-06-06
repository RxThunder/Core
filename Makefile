ci:
	vendor/bin/php-cs-fixer fix --config=.php_cs.dist --dry-run
	vendor/bin/phpcs
	vendor/bin/phpstan analyse
	vendor/bin/psalm
	vendor/bin/phpunit
