<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_created_listed_updated_and_soft_deleted(): void
    {
        $payload = [
            'name' => 'Gạo sạch',
            'price' => 25000,
            'unit' => 'kg',
            'stock' => 12,
        ];

        $this->postJson('/api/products', $payload)
            ->assertCreated()
            ->assertJsonPath('name', 'Gạo sạch')
            ->assertJsonPath('stock', 12);

        $product = Product::query()->firstOrFail();

        $this->getJson('/api/products?page=1&limit=20')
            ->assertOk()
            ->assertJsonPath('data.0.id', $product->id)
            ->assertJsonPath('per_page', 20);

        $this->patchJson('/api/products/'.$product->id, ['stock' => 0])
            ->assertOk()
            ->assertJsonPath('stock', 0);

        $this->deleteJson('/api/products/'.$product->id)
            ->assertNoContent();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->getJson('/api/products/'.$product->id)->assertNotFound();
        $this->getJson('/api/products')->assertJsonCount(0, 'data');
    }

    public function test_product_validation_rejects_non_positive_price_and_negative_stock(): void
    {
        $payload = [
            'name' => 'Rau hữu cơ',
            'price' => 0,
            'unit' => 'bó',
            'stock' => -1,
        ];

        $this->postJson('/api/products', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['price', 'stock']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_listing_paginates_using_requested_page_and_limit(): void
    {
        foreach (range(1, 3) as $number) {
            Product::create([
                'name' => 'Nông sản '.$number,
                'price' => 10000,
                'unit' => 'kg',
                'stock' => $number,
            ]);
        }

        $this->getJson('/api/products?page=2&limit=2')
            ->assertOk()
            ->assertJsonPath('current_page', 2)
            ->assertJsonPath('per_page', 2)
            ->assertJsonCount(1, 'data');
    }
}
