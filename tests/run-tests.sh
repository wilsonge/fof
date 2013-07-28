#!/bin/sh
phpunit --bootstrap=unit/bootstrap.php --colors "$@" .
