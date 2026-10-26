<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $products = $this->productService->paginate(
            (int) ($query['page'] ?? 1),
            (int) ($query['limit'] ?? 20)
        );

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'gt:0'],
            'unit' => ['required', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        return response()->json($this->productService->create($data), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productService->find($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'unit' => ['sometimes', 'required', 'string', 'max:50'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        if ($data === []) {
            throw ValidationException::withMessages([
                'product' => ['Cần cung cấp ít nhất một trường để cập nhật.'],
            ]);
        }

        return response()->json($this->productService->update($id, $data));
    }

    public function destroy(int $id): Response
    {
        $this->productService->delete($id);

        return response()->noContent();
    }
}
