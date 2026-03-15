<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductsRequest;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Product;
use Exception;

class ProductController
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('brand')) {
                $query->whereRaw('LOWER(brand) = ?', [strtolower($request->brand)]);
            }
            if ($request->filled('stock')) {
                $stockValue = trim($request->stock);

                // Regex: Captura operador (opcional) e número (obrigatório)
                if (preg_match('/^(>|<|>=|<=)?(\d+)$/', $stockValue, $matches)) {

                    // CORREÇÃO 1: Se o grupo 1 for vazio, usa '=' explicitamente
                    $operator = empty($matches[1]) ? '=' : $matches[1];

                    // CORREÇÃO 2: Garante que o valor seja inteiro
                    $value = (int) $matches[2];

                    // Aplica o filtro
                    $query->where('stock', $operator, $value);
                }
            }

            $query->orderBy('stock', 'asc');

            $products = $query->get();

            if ($products->isEmpty()) {
                return Response::error('Nenhum produto encontrado', 404);
            }

            return Response::success($products, 'Produtos listados com sucesso');

        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return Response::success($product, 'Produto encontrado');

        } catch (ModelNotFoundException $e) {
            return Response::error('Produto não encontrado', 404);

        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {

            $product = Product::create($request->validated());

            return Response::success($product, 'Produto cadastrado com sucesso', 201);

        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);

        }
    }

    public function update(StoreProductRequest $request, $id)
    {
        try {

            $product = Product::findOrFail($id);

            $product->update($request->validated());

            return Response::success($product, 'Produto atualizado com sucesso');

        } catch (ModelNotFoundException $e) {
            return Response::error('Produto não encontrado', 404);

        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);

        }
    }

    public function destroy(DeleteProductsRequest $request)
    {
        try {
            $ids = $request->data;

            $deletedCount = Product::whereIn('id', $ids)->delete();

            return Response::success(
                ['deleted_count' => $deletedCount],
                "produto(s) deletado(s) com sucesso"
            );

        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
