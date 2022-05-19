<?php


namespace App\Converters;


interface ConverterContract
{
    /**
     * Return list of supported currencies
     *
     * @return array
     */
    public function currencies(): array;

    /**
     * Convert from one currency to another
     *
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return array
     */
    public function convert(string $from, string $to, float $amount): array;

    /**
     * Provide help message to the concrete service
     *
     * @return array
     */
    public function help(): array;
}
