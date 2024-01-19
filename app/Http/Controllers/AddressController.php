<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    private array $rules = [];

    public function __construct() {
        $this->rules = [
            'customer_id' => ['exists:customers,id'],
            'address' => ['required', 'string'],
            'district' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'postal_code' => ['required', 'numeric'],
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $addresses = Address::all();

        return $this->jsonResponse(true, $addresses);
    }

    public function show(Address $address): JsonResponse
    {
        return $this->jsonResponse(true, $address->load('customer'));
    }

    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $filtered = $request->validate($this->rules);

            $address = new Address();
            $address->fill($filtered)->save();

            DB::commit();

            $isSuccess = true;
            $status = 200;
            $message = config('const.message.store.success');
        } catch (\Exception $e) {
            DB::rollback();

            $isSuccess = false;
            $address = null;
            $status = 421;
            $message = $e->getMessage();
        }

        return $this->jsonResponse($isSuccess, $address, $status, $message);
    }

    public function update(Request $request, Address $address): JsonResponse
    {
        DB::beginTransaction();
        try {
            $filtered = $request->validate($this->rules);
            $address->fill($filtered)->save();

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

        return $this->jsonResponse($isSuccess, $address, $status, $message);
    }

    public function destroy(Address $address): JsonResponse
    {
        DB::beginTransaction();
        try {
            $address->delete();

            DB::commit();

            $address = null;
            $isSuccess = true;
            $status = 200;
            $message = config('const.message.delete.success');
        } catch (\Exception $e) {
            DB::rollback();

            $isSuccess = false;
            $status = 421;
            $message = $e->getMessage();
        }

        return $this->jsonResponse($isSuccess, $address, $status, $message);
    }
}
