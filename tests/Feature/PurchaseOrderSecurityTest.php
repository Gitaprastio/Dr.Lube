<?php

namespace Tests\Feature;

use App\Model\DetailUser;
use App\Model\ListItem;
use App\Model\Product;
use App\Model\ProductAgreement;
use App\Model\PurchaseOrder;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_purchase_routes()
    {
        $this->get('/purchase')->assertRedirect('/login');
        $this->post('/purchase', [])->assertRedirect('/login');
    }

    public function test_user_cannot_view_another_users_purchase_order()
    {
        $owner = $this->createUser('owner@example.com');
        $other = $this->createUser('other@example.com');
        $order = $this->createPurchaseOrder($owner, 100);

        $this->actingAs($other)
            ->get(route('purchase.show', $order->id))
            ->assertStatus(404);
    }

    public function test_purchase_amount_is_recalculated_from_authorized_items()
    {
        $buyer = $this->createUser('buyer@example.com');
        $listItem = $this->createAgreementItem($buyer, 125.50, 20);

        $this->actingAs($buyer)
            ->post(route('purchase.store'), [
                'list_id' => [$listItem->id],
                'quantity' => [3],
                'total_price' => 1,
                'shipping_address' => 'Jl. Test 1',
            ])
            ->assertRedirect(route('purchase.index'));

        $this->assertDatabaseHas('purchase_orders', [
            'user_id' => $buyer->id,
            'amount' => 376.5,
            'shipping_address' => 'Jl. Test 1',
            'status' => 1,
        ]);

        $this->assertDatabaseHas('purchase_items', [
            'list_item_id' => $listItem->id,
            'quantity' => 3,
        ]);
    }

    public function test_purchase_uses_default_user_address_when_form_address_is_not_submitted()
    {
        $buyer = $this->createUser('buyer-default-address@example.com');
        $listItem = $this->createAgreementItem($buyer, 50, 5);

        $this->actingAs($buyer)
            ->post(route('purchase.store'), [
                'list_id' => [$listItem->id],
                'quantity' => [2],
            ])
            ->assertRedirect(route('purchase.index'));

        $this->assertDatabaseHas('purchase_orders', [
            'user_id' => $buyer->id,
            'amount' => 100,
            'shipping_address' => 'Jl. Default',
            'status' => 1,
        ]);
    }

    public function test_user_cannot_purchase_items_from_another_users_agreement()
    {
        $buyer = $this->createUser('buyer@example.com');
        $other = $this->createUser('other-agreement@example.com');
        $foreignListItem = $this->createAgreementItem($other, 99, 10);

        $this->actingAs($buyer)
            ->from(route('purchase.order'))
            ->post(route('purchase.store'), [
                'list_id' => [$foreignListItem->id],
                'quantity' => [1],
                'shipping_address' => 'Jl. Test 1',
            ])
            ->assertRedirect(route('purchase.order'))
            ->assertSessionHasErrors('list_id');

        $this->assertDatabaseMissing('purchase_orders', [
            'user_id' => $buyer->id,
        ]);
    }

    public function test_user_cannot_purchase_more_than_contracted_quantity()
    {
        $buyer = $this->createUser('buyer-limit@example.com');
        $listItem = $this->createAgreementItem($buyer, 75, 2);

        $this->actingAs($buyer)
            ->from(route('purchase.order'))
            ->post(route('purchase.store'), [
                'list_id' => [$listItem->id],
                'quantity' => [3],
                'shipping_address' => 'Jl. Test 1',
            ])
            ->assertRedirect(route('purchase.order'))
            ->assertSessionHasErrors('quantity');
    }

    private function createUser($email)
    {
        $user = User::create([
            'name' => $email,
            'email' => $email,
            'password' => bcrypt('secret'),
        ]);

        $detail = new DetailUser;
        $detail->user_id = $user->id;
        $detail->organization_name = $email;
        $detail->address = 'Jl. Default';
        $detail->save();

        return $user;
    }

    private function createAgreementItem(User $user, $cost, $quantity)
    {
        $product = new Product;
        $product->product_code = 'P-'.$user->id.'-'.$cost;
        $product->product_name = 'Product '.$user->id;
        $product->product_desc = 'Test product';
        $product->save();

        $agreement = new ProductAgreement;
        $agreement->user_id = $user->id;
        $agreement->contract_date_start = '2026-01-01';
        $agreement->contract_date_end = '2026-12-31';
        $agreement->save();

        $item = new ListItem;
        $item->cost = $cost;
        $item->quantity = $quantity;
        $item->product_agreement_id = $agreement->id;
        $item->product_id = $product->id;
        $item->save();

        return $item;
    }

    private function createPurchaseOrder(User $user, $amount)
    {
        $order = new PurchaseOrder;
        $order->status = 1;
        $order->amount = $amount;
        $order->shipping_address = 'Jl. Owner';
        $order->shipping_date = now();
        $order->user_id = $user->id;
        $order->save();

        return $order;
    }
}
