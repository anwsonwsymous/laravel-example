<?php


namespace App\Http\Controllers\Api;


use App\Converters\ConverterContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertRequest;

class ConverterController extends Controller
{
    /**
     * @var ConverterContract
     */
    private $converter;

    /**
     * ConverterController constructor.
     *
     * @param ConverterContract $converter
     */
    public function __construct(ConverterContract $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Return help message json
     *
     * @return array
     */
    public function help()
    {
        return $this->converter->help();
    }

    /**
     * Convert from one currency to another
     *
     * @param ConvertRequest $request
     * @return array
     */
    public function convert(ConvertRequest $request)
    {
        return $this->converter->convert($request->from, $request->to, $request->amount);
    }

    /**
     * Return all supported currencies list
     *
     * @return array
     */
    public function currencies()
    {
        return $this->converter->currencies();
    }
}
