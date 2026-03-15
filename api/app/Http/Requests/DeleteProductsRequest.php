<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class DeleteProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'required|array|min:1',
            'data.*' => 'required|integer|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'data.required' => 'É necessário informar os IDs dos produtos.',
            'data.array' => 'Os IDs devem ser enviados em um array.',
            'data.min' => 'É necessário informar pelo menos um produto.',
            'data.*.required' => 'Todos os IDs são obrigatórios.',
            'data.*.integer' => 'Os IDs devem ser números inteiros.',
            'data.*.exists' => 'Um ou mais produtos não foram encontrados.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $firstError = collect($validator->errors()->messages())
            ->flatten()
            ->first();

        throw new HttpResponseException(
            Response::error($firstError, 422)
        );
    }
}