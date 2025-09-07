<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class WeddingIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_date' => ['sometimes', 'date_format:Y-m-d'],
            'event_date_from' => ['sometimes', 'date_format:Y-m-d'],
            'event_date_to' => ['sometimes', 'date_format:Y-m-d'],
            'location' => ['sometimes', 'string', 'max:255'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
