<?php


namespace App\Http\Requests;


use App\Converters\ConverterContract;
use Illuminate\Foundation\Http\FormRequest;

class ConvertRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $service = app(ConverterContract::class);
        $currencies = implode(',', $service->currencies());
        return [
            'from' => "required|string|max:3|in:$currencies",
            'to' => "required|string|max:3|in:$currencies",
            'amount' => 'required|numeric',
        ];
    }
}
