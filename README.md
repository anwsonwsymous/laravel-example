## Installation
For the first time, run one of bellow commands.
- On windows run : `start.bat`
- On linux run: `start.sh`

After first installation, use 
`docker-compose down` to shut down or `docker-compose up -d` to start containers.

## Endpoints

- [http://localhost:81/api/v1/help](http://localhost:81/api/v1/help)
    - get "how to use" message
- [http://localhost:81/api/v1/currencies](http://localhost:81/api/v1/currencies)
    - get the list of supported currencies
- [http://localhost:81/api/v1/convert?from=USD&to=RUB&amount=1](http://localhost:81/api/v1/convert?from=USD&to=RUB&amount=1)
    - convert 1 USD to RUB
