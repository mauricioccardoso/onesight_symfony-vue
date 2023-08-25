#!/bin/bash

composer install

~/backend/bin/console lexik:jwt:generate-keypair

~/backend/bin/console doctrine:migrations:migrate

symfony serve

# tail -f /dev/null