#!/bin/sh
php `which phpunit` -c configuration.xml "$@" .
