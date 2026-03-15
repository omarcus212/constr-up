<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'O nome do produto é obrigatório.',
            'name.string' => 'O nome do produto deve ser um texto válido.',
            'name.max' => 'O nome do produto não pode exceder 255 caracteres.',

            // Description
            'description.string' => 'A descrição deve ser um texto válido.',

            // Brand
            'brand.required' => 'A marca do produto é obrigatória.',
            'brand.string' => 'A marca deve ser um texto válido.',
            'brand.max' => 'A marca não pode exceder 255 caracteres.',

            // Price
            'price.required' => 'O preço do produto é obrigatório.',
            'price.numeric' => 'O preço deve ser um valor numérico válido.',
            'price.min' => 'O preço deve ser maior que zero.',

            // Stock
            'stock.required' => 'O estoque do produto é obrigatório.',
            'stock.integer' => 'O estoque deve ser um número inteiro.',
            'stock.min' => 'O estoque não pode ser negativo.',
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
