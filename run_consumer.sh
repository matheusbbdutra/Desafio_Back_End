#!/bin/bash
docker-compose exec app php bin/console messenger:consume async_priority_high -vv