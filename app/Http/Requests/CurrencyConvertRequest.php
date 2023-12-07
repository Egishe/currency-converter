<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $from
 * @property-read string $to
 * @property-read float $amount
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
            'from' => 'required|string',
            'to' => 'required|string',
            'amount' => 'required|numeric|min:0.1',
        ];
    }
}
