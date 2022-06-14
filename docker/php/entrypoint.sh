#!/bin/sh

ls -la
echo "Running entrypoint.sh"

chmod -R 777 ./bootstrap/cache
chmod -R 777 ./storage

composer update

exec "$@"
