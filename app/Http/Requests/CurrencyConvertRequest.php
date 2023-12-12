<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $currency_from
 * @property-read string $currency_to
 * @property-read string $value
 */
class CurrencyConvertRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'currency_from' => 'required|string',
            'currency_to' => 'required|string',
            'value' => 'required|numeric|min:0.01',
        ];
    }
}
