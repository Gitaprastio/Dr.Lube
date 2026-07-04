<?php

namespace Tests\Feature;

use App\Model\Complaint;
use App\Model\PurchaseOrder;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WorkflowAndComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function test_sensitive_dashboard_routes_require_authentication()
    {
        $this->get('/botman/tinker')->assertRedirect('/login');
        $this->postJson('/botman', [
            'driver' => 'web',
            'userId' => 'guest',
            'message' => 'Hi',
        ])->assertUnauthorized();
        $this->get('/botman/chat')->assertStatus(404);
        $this->get(route('sales.index'))->assertRedirect('/login');
        $this->get(route('operation.index'))->assertRedirect('/login');
        $this->get(route('complain.index'))->assertRedirect('/login');
    }

    public function test_role_dashboard_routes_reject_users_without_required_role()
    {
        $user = User::create([
            'name' => 'No Role',
            'email' => 'no-role@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->actingAs($user)->get(route('sales.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('operation.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('complain.index'))->assertStatus(403);
    }

    public function test_authenticated_user_can_open_first_party_botman_tinker()
    {
        $user = User::create([
            'name' => 'Tinker User',
            'email' => 'tinker@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->actingAs($user)
            ->get('/botman/tinker')
            ->assertOk()
            ->assertSee('id="chat-input"', false)
            ->assertSee("var endpoint = 'http://localhost/botman';", false)
            ->assertSee("'Accept': 'application/json'", false)
            ->assertSee("'X-CSRF-TOKEN'", false)
            ->assertDontSee('<botman-tinker', false);
    }

    public function test_authenticated_user_can_post_to_botman_web_driver()
    {
        $user = User::create([
            'name' => 'Bot User',
            'email' => 'bot-user@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->actingAs($user)
            ->post('/botman', [
                'driver' => 'web',
                'userId' => (string) $user->id,
                'message' => 'Hi',
                'interactive' => '0',
            ], [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            ])
            ->assertOk()
            ->assertJsonPath('messages.0.text', 'Hello!');
    }

    public function test_sales_can_only_accept_pending_orders()
    {
        $sales = $this->createRoleUser('sales@example.com', 'sales');
        $pending = $this->createPurchaseOrder(1);
        $alreadyAccepted = $this->createPurchaseOrder(2);

        $this->actingAs($sales)
            ->put(route('sales.accept', $pending->id))
            ->assertRedirect(route('sales.index'));

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $pending->id,
            'status' => 2,
        ]);

        $this->actingAs($sales)
            ->put(route('sales.accept', $alreadyAccepted->id))
            ->assertStatus(404);

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $alreadyAccepted->id,
            'status' => 2,
        ]);
    }

    public function test_sales_can_only_reject_pending_orders()
    {
        $sales = $this->createRoleUser('sales-reject@example.com', 'sales');
        $pending = $this->createPurchaseOrder(1);
        $sent = $this->createPurchaseOrder(3);

        $this->actingAs($sales)
            ->put(route('sales.reject', $pending->id))
            ->assertRedirect(route('sales.index'));

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $pending->id,
            'status' => 90,
        ]);

        $this->actingAs($sales)
            ->put(route('sales.reject', $sent->id))
            ->assertStatus(404);

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $sent->id,
            'status' => 3,
        ]);
    }

    public function test_operation_can_only_send_accepted_orders()
    {
        $operation = $this->createRoleUser('operation@example.com', 'operation');
        $accepted = $this->createPurchaseOrder(2);
        $pending = $this->createPurchaseOrder(1);

        $this->actingAs($operation)
            ->put(route('operation.send', $accepted->id))
            ->assertRedirect(route('operation.index'));

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $accepted->id,
            'status' => 3,
        ]);

        $this->actingAs($operation)
            ->put(route('operation.send', $pending->id))
            ->assertStatus(404);

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $pending->id,
            'status' => 1,
        ]);
    }

    public function test_complaint_store_persists_status_and_user()
    {
        $complaintUser = $this->createRoleUser('complaint@example.com', 'complain');

        $this->actingAs($complaintUser)
            ->post(route('complain.store'), [
                'subject' => 'Late delivery',
                'complaint_desc' => 'The delivery arrived late.',
            ])
            ->assertRedirect(route('complain.index'));

        $this->assertDatabaseHas('complaints', [
            'subject' => 'Late delivery',
            'complaint_desc' => 'The delivery arrived late.',
            'user_id' => $complaintUser->id,
            'status' => 1,
        ]);
    }

    public function test_complaint_index_reads_database_rows()
    {
        $complaintUser = $this->createRoleUser('complaint-index@example.com', 'complain');

        $complaint = new Complaint;
        $complaint->subject = 'Quality issue';
        $complaint->complaint_desc = 'Oil quality issue';
        $complaint->status = 1;
        $complaint->user_id = $complaintUser->id;
        $complaint->save();

        $this->actingAs($complaintUser)
            ->get(route('complain.index'))
            ->assertOk()
            ->assertSee('Quality issue')
            ->assertSee($complaintUser->name);
    }

    private function createRoleUser($email, $roleName)
    {
        Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $user = User::create([
            'name' => $email,
            'email' => $email,
            'password' => bcrypt('secret'),
        ]);

        $user->assignRole($roleName);

        return $user;
    }

    private function createPurchaseOrder($status)
    {
        $user = User::create([
            'name' => 'buyer-'.$status.'-'.Str::random(6),
            'email' => 'buyer-'.$status.'-'.Str::random(6).'@example.com',
            'password' => bcrypt('secret'),
        ]);

        $order = new PurchaseOrder;
        $order->status = $status;
        $order->amount = 100;
        $order->shipping_address = 'Jl. Workflow';
        $order->shipping_date = now();
        $order->user_id = $user->id;
        $order->save();

        return $order;
    }
}
