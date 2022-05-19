# Start docker
docker-compose up -d

# Install composer
docker-compose exec converter-api composer install
# Copy .env
docker-compose exec converter-api cp .env.example .env

echo "Finished!!!"
