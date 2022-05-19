<?php


namespace App\Converters;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CbrService implements ConverterContract
{
    /**
     * Client used to request cbr.ru api
     *
     * @var Client
     */
    private $client;

    /**
     * cbr.ru api url to get daily currencies
     *
     * @var string
     */
    private $url = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * CbRuService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function currencies(): array
    {
        $responseArray = $this->request();
        if (isset($responseArray['error'])) {
            return $responseArray;
        }

        $supportedCurrencies = array_values(array_map(function(\SimpleXMLElement $currency) {
            return (string) $currency->CharCode;
        }, $responseArray));
        return array_merge($supportedCurrencies, ["RUB"]);
    }

    /**
     * @inheritDoc
     */
    public function convert(string $from, string $to, float $amount): array
    {
        $responseArray = $this->request();
        if (isset($responseArray['error'])) {
            return $responseArray;
        }

        // Find from
        $fromRate = $this->findRate($responseArray, $from);
        // Find to
        $toRate = $this->findRate($responseArray, $to);

        return [
            'amount' => $fromRate > 0 && $toRate > 0 ? ($fromRate / $toRate) * $amount : 0
        ];
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
     * Find rate for provided currency by CharCode
     *
     * @param array $responseArray
     * @param string $charCode
     * @return float
     */
    private function findRate(array $responseArray, string $charCode): float
    {
        if ($charCode === 'RUB') {
            return 1;
        }

        $filtered = array_filter($responseArray, function (\SimpleXMLElement $currency) use($charCode){
            return $currency->CharCode == $charCode;
        });
        $currency = reset($filtered);
        if (!$currency) {
            return 0;
        }

        return ((float)str_replace(",", ".", $currency->Value)) / $currency->Nominal;
    }

    /**
     * Request and get all currencies with rates
     *
     * @return array
     * @throws \Exception
     */
    private function request(): array
    {
        $fullUrl = $this->url;
        try {
            $response = $this->client->get($fullUrl, []);
            $xmlResponse = new \SimpleXMLElement($response->getBody()->getContents());
            return ((array) $xmlResponse)['Valute'];
        } catch (GuzzleException $e) {
            return [
                'error' => 'Something went wrong, please try again later.'
            ];
        }
    }
}
