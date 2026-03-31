Развернуть проект:

1. cp ./src/.env.example ./src/.env
2. docker compose run php composer install
3. docker compose up -d
4. docker exec php php migration.php

После этого проект должен открытбся по адресу http://localhost/

API описано в postman ./api