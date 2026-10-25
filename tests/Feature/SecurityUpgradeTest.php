<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;
use Stripe\HttpClient\CurlClient;
use Stripe\Stripe;
use Tests\TestCase;

class SecurityUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        Schema::create('cities', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        config(['cashier.webhook.secret' => 'whsec_security_fixture']);
    }

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(new CurlClient);
        parent::tearDown();
    }

    public function test_existing_public_pages_and_admin_authentication(): void
    {
        foreach (['/', '/donate', '/contact', '/about', '/leaderboard', '/albums', '/privacy', '/admin'] as $uri) {
            $this->get($uri)->assertOk();
        }
        $this->get('/admin/dashboard')->assertRedirect('/admin');
        $user = User::factory()->create(['password' => Hash::make('Synthetic-Test-Password')]);
        $this->post('/admin/signin', ['email' => $user->email, 'password' => 'Synthetic-Test-Password'])->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->get('/admin/dashboard')->assertOk();
    }

    public function test_checkout_validation_prevents_a_stripe_request(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->never())->method('request');
        ApiRequestor::setHttpClient($client);
        $this->postJson('/process-checkout', [])->assertUnprocessable()->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'amount']);
    }

    public function test_checkout_preserves_amount_currency_and_account_default_api_version(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('request')->willReturnCallback(function ($method, $url, $headers, $params): array {
            $this->assertSame('post', $method);
            $this->assertStringEndsWith('/v1/checkout/sessions', $url);
            $this->assertSame(12500, $params['line_items'][0]['price_data']['unit_amount']);
            $this->assertSame('INR', $params['line_items'][0]['price_data']['currency']);
            $this->assertSame('payment', $params['mode']);
            $this->assertNull(Stripe::getApiVersion());

            return [json_encode(['id' => 'cs_test_synthetic', 'object' => 'checkout.session', 'url' => 'https://checkout.stripe.com/synthetic']), 200, []];
        });
        ApiRequestor::setHttpClient($client);
        $this->post('/process-checkout', ['first_name' => 'Synthetic', 'last_name' => 'Donor', 'email' => 'synthetic@example.invalid', 'amount' => 125, 'country' => 1, 'state' => 1, 'city' => 1])->assertRedirect('https://checkout.stripe.com/synthetic');
        $this->assertDatabaseHas('donation', ['session_id' => 'cs_test_synthetic', 'amount' => 125, 'status' => 'unpaid']);
    }

    public function test_stripe_webhook_accepts_valid_signatures_and_rejects_invalid_ones(): void
    {
        $body = json_encode(['id' => 'evt_synthetic', 'object' => 'event', 'type' => 'security.verification', 'data' => ['object' => []]]);
        $time = time();
        $signature = hash_hmac('sha256', $time.'.'.$body, 'whsec_security_fixture');
        $this->call('POST', '/stripe/webhook', [], [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_STRIPE_SIGNATURE' => 't='.$time.',v1='.$signature], $body)->assertOk();
        $this->call('POST', '/stripe/webhook', [], [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_STRIPE_SIGNATURE' => 't='.$time.',v1=invalid'], $body)->assertForbidden();
    }

    public function test_cashier_schema_has_the_documented_type_column_and_price_index(): void
    {
        $this->assertTrue(Schema::hasColumn('subscriptions', 'type'));
        $this->assertFalse(Schema::hasColumn('subscriptions', 'name'));
        $indexes = collect(Schema::getIndexes('subscription_items'));
        $this->assertTrue($indexes->contains(fn (array $index): bool => $index['columns'] === ['subscription_id', 'stripe_price'] && ! $index['unique']));
    }

    public function test_admin_tables_keep_their_json_contract_and_permission_boundary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->getJson('/admin/donation', ['X-Requested-With' => 'XMLHttpRequest'])->assertOk()->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
        $viewer = User::factory()->create(['role' => 'user']);
        $this->actingAs($viewer)->getJson('/admin/donation', ['X-Requested-With' => 'XMLHttpRequest'])->assertUnauthorized();
    }

    public function test_contact_form_keeps_validation_and_local_persistence(): void
    {
        $this->postJson('/contact', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'mobile', 'message']);
        $this->post('/contact', ['name' => 'Synthetic Donor', 'email' => 'synthetic@example.invalid', 'mobile' => '9876543210', 'message' => 'Synthetic security verification'])->assertRedirect();
        $this->assertDatabaseHas('contacts', ['email' => 'synthetic@example.invalid']);
    }

    public function test_payment_success_and_cancel_callbacks_still_parse_stripe_sessions(): void
    {
        foreach ([['payment-success', 'paid', 'paid'], ['failed-payment', 'unpaid', 'failed']] as [$route, $paymentStatus, $expectedStatus]) {
            $id = 'cs_test_'.$expectedStatus;
            Donation::query()->forceCreate(['session_id' => $id, 'amount' => 125, 'status' => 'unpaid', 'name' => 'Synthetic Donor', 'email' => 'synthetic@example.invalid', 'city_id' => 1, 'state_id' => 1, 'country_id' => 1]);
            $client = $this->createMock(ClientInterface::class);
            $client->expects($this->once())->method('request')->willReturn([json_encode(['id' => $id, 'object' => 'checkout.session', 'payment_status' => $paymentStatus]), 200, []]);
            ApiRequestor::setHttpClient($client);
            $this->get('/'.$route.'?session_id='.$id)->assertRedirect('/donate');
            $this->assertDatabaseHas('donation', ['session_id' => $id, 'status' => $expectedStatus]);
        }
    }
}
