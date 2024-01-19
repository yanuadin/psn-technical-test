<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function test_get_address_list(): void
    {
        //Create address dummy
        Address::factory()->create();

        //Send request to get address list
        $response = $this->get('/api/address');

        //Asserting Json
        $this->assertingJson($response);
    }

    public function test_show_address(): void
    {
        //Create address dummy
        $address = Address::factory()->create();

        //Send request to get address list
        $response = $this->get('/api/address/' . $address->id);

        //Asserting Json
        $this->assertingJson($response);
    }

    public function test_store_address(): void
    {
        //Prepare array data
        $customer = Customer::factory()->create();

        $address = [
            'customer_id' => $customer->id,
            'address' => fake('id_ID')->address(),
            'district' => 'Rungkut',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => rand(10000, 99999)
        ];

        //Send request to post new address data
        $response = $this->post('/api/address', $address);

        //Asserting Json
        $this->assertingJson($response);

        //Asserting Database
        $this->assertDatabaseHas('addresses', $address);
    }

    public function test_update_address(): void
    {
        //Create dummy data using factory
        $address = Address::factory()->create();
        $newCustomer = Customer::factory()->create();

        //Prepare data that will be updated
        $newAddress = [
            'customer_id' => $newCustomer->id,
            'address' => fake('id_ID')->address(),
            'district' => 'NEW Rungkut',
            'city' => 'NEW Surabaya',
            'province' => 'NEW Jawa Timur',
            'postal_code' => rand(10000, 99999)
        ];

        //Send request to post new address data
        $response = $this->put('/api/address/' . $address->id, $newAddress);

        //Asserting Json
        $this->assertingJson($response);

        //Asserting Database
        $this->assertDatabaseHas('addresses', $newAddress);
    }

    public function test_delete_address(): void
    {
        //Create dummy data using factory
        $address = Address::factory()->create();

        //Send request to delete address data
        $response = $this->delete('/api/address/' . $address->id);

        //Asserting Json
        $this->assertingJson($response);

        //Asserting deleted data in database
        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }

    private function assertingJson(TestResponse $response): void
    {
        //Asserting response json
        $response
            ->assertStatus(200)
            ->assertJson(['is_success' => true])
            ->assertJsonStructure(['is_success', 'message', 'data']);
    }
}
