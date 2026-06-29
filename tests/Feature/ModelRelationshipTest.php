<?php

namespace Tests\Feature;

use App\Model\DetailProduct;
use App\Model\DetailUser;
use App\Model\Product;
use App\Model\ProductAgreement;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_fixed_relationships_match_schema_foreign_keys()
    {
        $user = User::create([
            'name' => 'Relationship User',
            'email' => 'relationship@example.com',
            'password' => bcrypt('secret'),
        ]);

        $detailUser = new DetailUser;
        $detailUser->user_id = $user->id;
        $detailUser->organization_name = 'Relationship Org';
        $detailUser->address = 'Jl. Relationship';
        $detailUser->save();

        $product = new Product;
        $product->product_code = 'REL-001';
        $product->product_name = 'Relationship Oil';
        $product->product_desc = 'Relationship product';
        $product->save();

        $detailProduct = new DetailProduct;
        $detailProduct->product_id = $product->id;
        $detailProduct->stock = 500;
        $detailProduct->save();

        $agreement = new ProductAgreement;
        $agreement->user_id = $user->id;
        $agreement->contract_date_start = '2026-01-01';
        $agreement->contract_date_end = '2026-12-31';
        $agreement->save();

        $this->assertTrue($user->detailUser->is($detailUser));
        $this->assertTrue($detailUser->user->is($user));
        $this->assertTrue($product->detail->is($detailProduct));
        $this->assertTrue($detailProduct->product->is($product));
        $this->assertTrue($agreement->user->is($user));
        $this->assertCount(1, $user->agreement);
    }
}
