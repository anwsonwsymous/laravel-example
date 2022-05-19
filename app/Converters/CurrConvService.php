<?php


namespace App\Converters;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class CurrConvService
 *
 * @package App\Converters
 */
class CurrConvService implements ConverterContract
{
    /**
     * Client used to request 3rd party api
     *
     * @var Client
     */
    private $client;

    /**
     * currconv service api url
     *
     * @var string
     */
    private $url = 'https://free.currconv.com/api/v7/';

    /**
     * User api key
     *
     * @var string
     */
    private $apiKey;

    /**
     * ConverterService constructor.
     *
     * @param Client $client
     * @param string $apiKey
     */
    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritDoc
     */
    public function currencies(): array
    {
        $responseArray = $this->request("currencies");

        return isset($responseArray['results']) ? array_values(array_map(function($currency) {
            return $currency['id'];
        }, $responseArray['results'])) : [];
    }

    /**
     * @inheritDoc
     */
    public function convert(string $from, string $to, float $amount): array
    {
        $responseArray = $this->request("convert", [
            'compact' => 'ultra',
            'q' => "{$from}_{$to}"
        ]);

        $result = $responseArray["{$from}_{$to}"] ?? null;
        if ($result) {
            return [
                'amount' => $amount * $result
            ];
        }

        return $responseArray;
    }

    /**
     * @inheritDoc
     */
    public function help(): array
    {
        return [
            'message' => 'To test api, you should make HTTP request to the endpoints with provided options.',
            'endpoints' => [
                [
                    'endpoint' => '/convert',
                    'method' => 'GET',
                    'options' => [
                        'from' => 'USD',
                        'to' => 'RUB',
                        'amount' => 100
                    ],
                    'response_example' => $this->convert('USD', 'RUB',100),
                ],
                [
                    'endpoint' => '/currencies',
                    'method' => 'GET',
                    'options' => [],
                    'response_example' => $this->currencies(),
                ]
            ],
        ];
    }

    /**
     * Request and get response
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    private function request(string $endpoint, array $params = []): array
    {
        $params['apiKey'] = $this->apiKey;
        $fullUrl = $this->url . $endpoint . "?" .  http_build_query($params);
        try {
            $response = $this->client->get($fullUrl, []);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return [
                'error' => 'Something went wrong, please try again later.'
            ];
        }
    }
}
