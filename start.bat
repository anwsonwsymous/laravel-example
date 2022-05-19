@echo off

REM Start docker
docker-compose up -d

REM Install composer
docker-compose exec converter-api composer install
REM Copy .env
docker-compose exec converter-api cp .env.example .env

echo Finished!!!
