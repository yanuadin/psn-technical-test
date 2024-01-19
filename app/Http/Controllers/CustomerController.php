<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    private array $rules = [];

    public function __construct() {
        $this->rules = [
            'title' => ['required', 'string'],
            'name' => ['required', 'string'],
            'gender' => ['required', 'string', Rule::in(Arr::pluck(Customer::getGenders(), 'value'))],
            'phone_number' => ['required', 'string'],
            'image' => ['image', 'max:2048'],
            'email' => ['required', 'email', 'string', 'unique:customers,email'],
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $customers = Customer::all();

        return $this->jsonResponse(true, $customers);
    }

    public function show(Customer $customer): JsonResponse
    {
        return $this->jsonResponse(true, $customer->load('addresses'));
    }

    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $filtered = $request->validate($this->rules);
            $filtered['image'] = $this->storeFile($request, 'image', 'image');

            $customer = new Customer();
            $customer->fill($filtered)->save();

            DB::commit();

            $isSuccess = true;
            $status = 200;
            $message = config('const.message.store.success');
        } catch (\Exception $e) {
            DB::rollback();

            $isSuccess = false;
            $customer = null;
            $status = 421;
            $message = $e->getMessage();
        }

        return $this->jsonResponse($isSuccess, $customer, $status, $message);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $this->rules['email'] = ['required', 'email', 'string', 'unique:customers,email,' . $customer->id];

        DB::beginTransaction();
        try {
            $filtered = $request->validate($this->rules);
            $filtered['image'] = $this->updateFile($request, 'image', 'image', $customer, 'image');

            $customer->fill($filtered)->save();

            DB::commit();

            $isSuccess = true;
            $status = 200;
            $message = config('const.message.update.success');
        } catch (\Exception $e) {
            DB::rollback();

            $isSuccess = false;
            $status = 421;
            $message = $e->getMessage();
        }

        return $this->jsonResponse($isSuccess, $customer, $status, $message);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->deleteFile($customer, 'image');
            $customer->delete();

            DB::commit();

            $isSuccess = true;
            $customer = null;
            $status = 200;
            $message = config('const.message.delete.success');
        } catch (\Exception $e) {
            DB::rollback();

            $isSuccess = false;
            $status = 421;
            $message = $e->getMessage();
        }

        return $this->jsonResponse($isSuccess, $customer, $status, $message);
    }
}
