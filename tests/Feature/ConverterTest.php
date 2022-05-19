<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ConverterTest extends TestCase
{
    /**
     * Test converter
     *
     * @return void
     */
    public function test_convert()
    {
        $params = [
            'from' => 'USD',
            'to' => 'AMD',
            'amount' => 100
        ];
        $response = $this->json('GET', '/api/v1/convert', $params);
        $this->assertIsFloat($response['amount']);
    }

    /**
     * Test converter with not existing currency
     *
     * @expectedException \Illuminate\Validation\ValidationException
     * @return void
     */
    public function test_convert_not_existing_currency()
    {
        $params = [
            'from' => 'UUU',
            'to' => 'JJJ',
            'amount' => 100
        ];
        $response = $this->json('GET', '/api/v1/convert', $params);
        $response->assertJsonValidationErrors(['from', 'to']);
    }

    /**
     * Test converter without amount parameter
     *
     * @expectedException \Illuminate\Validation\ValidationException
     * @return void
     */
    public function test_convert_without_amount()
    {
        $params = [
            'from' => 'USD',
            'to' => 'RUB',
        ];
        $response = $this->json('GET', '/api/v1/convert', $params);
        $response->assertJsonValidationErrors(['amount']);
    }

    /**
     * Test converter with incorrect type of amount
     *
     * @expectedException \Illuminate\Validation\ValidationException
     * @return void
     */
    public function test_convert_with_incorrect_amount()
    {
        $params = [
            'from' => 'USD',
            'to' => 'RUB',
            'amount' => 'INCORRECT AMOUNT MUST BE NUMERIC'
        ];
        $response = $this->json('GET', '/api/v1/convert', $params);
        $response->assertJsonValidationErrors(['amount']);
    }

    /**
     * Test currencies count is 35
     *
     * @return void
     */
    public function test_currencies_count()
    {
        $response = $this->get('/api/v1/currencies');
        $response->assertJsonCount(35);
    }

    /**
     * Test currencies response is array
     *
     * @return void
     */
    public function test_currencies_array()
    {
        $response = $this->get('/api/v1/currencies');
        $response->assertJson(function(AssertableJson $res) {
            $this->assertIsArray($res->toArray());
        });
    }
}
