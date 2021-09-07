#!/bin/bash -e

PLAYGROUND_DIR=$(mktemp -d)
PROJECT_DIR="$(dirname "$(readlink -f "$0")")"

cd $PLAYGROUND_DIR

printf '{
    "repositories": [
        {
            "type": "path",
            "url": "%s"
        }
    ],
    "require": {
        "tomaszgasior/create-symfony-project": "dev-master"
    }
}' $PROJECT_DIR > composer.json

curl -s https://getcomposer.org/download/latest-2.x/composer.phar > composer-2
curl -s https://getcomposer.org/download/latest-1.x/composer.phar > composer-1
chmod +x composer-2 composer-1

echo 'Playground folder:   '$PLAYGROUND_DIR
echo 'How to install:      rm -rfv vendor/ composer.lock && ./composer-2 install'
echo 'How to test command: ./composer-2 create-symfony-project […]'
