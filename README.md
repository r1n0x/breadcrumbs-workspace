Status: work in progress
------
Setting up:

```bash
cd resources/docker && 
docker compose up --detach && 
docker compose exec php-fpm composer install &&
docker compose exec php-fpm php bin/console d:m:m --no-interaction
```

Website will be available at:
http://localhost:80