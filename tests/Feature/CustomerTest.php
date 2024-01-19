<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_get_customer_list(): void
    {
        //Create customer dummy
        Customer::factory()->create();

        //Send request to get customer list
        $response = $this->get('/api/customer');

        //Asserting Json
        $this->assertingJson($response);
    }

    public function test_show_customer(): void
    {
        //Create customer dummy
        $customer = Customer::factory()->create();

        //Send request to get customer list
        $response = $this->get('/api/customer/' . $customer->id);

        //Asserting Json
        $response
            ->assertStatus(200)
            ->assertJson(['is_success' => true])
            ->assertJsonStructure(['is_success', 'message', 'data' => ['addresses']]);

        //Asserting file in storage
        Storage::disk('public')->assertExists($response->json()['data']['image']);
    }

    public function test_store_customer(): void
    {
        //Prepare array data
        $gender = fake()->randomElement(Customer::getGenders());
        Storage::fake('local');

        $customer = [
            'title' => fake()->title(strtolower($gender['label'])),
            'name' => fake('id_ID')->name(strtolower($gender['label'])),
            'gender' => $gender['value'],
            'phone_number' => fake('id_ID')->phoneNumber(),
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'email' => fake('id_ID')->unique()->safeEmail()
        ];

        //Send request to post new customer data
        $response = $this->post('/api/customer', $customer);

        //Asserting Json
        $this->assertingJson($response);

        //Asserting file in storage
        $customer['image'] = $response->json()['data']['image'];
        Storage::disk('public')->assertExists($customer['image']);

        //Asserting Database
        $this->assertDatabaseHas('customers', $customer);
    }

    public function test_update_customer(): void
    {
        //Create dummy data using factory
        $customer = Customer::factory()->create();

        //Prepare data that will be updated
        Storage::fake('local');
        $newCustomer = [
            'title' => 'Mr',
            'name' => 'Yanu',
            'gender' => 'M',
            'phone_number' => '088888888888',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'email' => fake('id_ID')->unique()->safeEmail(),
        ];

        //Send request to post new customer data
        $response = $this->put('/api/customer/' . $customer->id, $newCustomer);

        //Asserting Json
        $this->assertingJson($response);

        //Asserting new file was insert in storage
        $newCustomer['image'] = $response->json()['data']['image'];
        Storage::disk('public')->assertExists($newCustomer['image']);

        //Asserting old image was deleted
        Storage::disk('public')->assertMissing($customer['image']);

        //Asserting Database
        $this->assertDatabaseHas('customers', $newCustomer);
    }

    public function test_delete_customer(): void
    {
        //Create dummy data using factory
        $customer = Customer::factory()->create();

        //Send request to delete customer data
        $response = $this->delete('/api/customer/' . $customer->id);

        //Asserting Json
        $this->assertingJson($response);

        //Asserting old image was deleted
        Storage::disk('public')->assertMissing($customer['image']);

        //Asserting deleted data in database
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
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
