<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function paginate(int $page, int $limit): LengthAwarePaginator
    {
        return Product::query()
            ->orderBy('id')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function find(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->find($id);
        $product->update($data);

        return $product->refresh();
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
