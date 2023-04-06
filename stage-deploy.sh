#use this code to rebuild docker containers as well.

git reset --hard && git pull \
&& docker-compose up -d --build \
&& docker-compose run --rm composer install \
&& docker-compose run --rm npm install \
&& docker-compose run --rm npm run build \
&& docker-compose run --rm artisan migrate --force \
&& docker-compose run --rm artisan optimize:clear \
&& docker-compose run --rm artisan optimize