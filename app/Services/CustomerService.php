<?php

namespace App\Services;


use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class CustomerService
 *
 * Service class responsible for customer-related operations.
 *
 * @package App\Services
 */
class CustomerService
{

    /**
     * Get all customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return Customer::all();
    }

    /**
     * Show a customer.
     *
     * @param int $id
     * @return \App\Models\Customer|null
     */
    public function show(int $id): ?Customer
    {
        return Customer::findOrFail($id);
    }

    public function create(array $request): Customer
    {
        $validatedData = $this->validateData($request);

        $customer = Customer::create($validatedData);

        return $customer;
    }


    public function update(array $request, int $id): ?Customer
    {
        $customer = $this->show($id);
        $validatedData = $this->validateData($request);

        $customer->fill($validatedData);
        $customer->save();
        // $customer = Customer::findOrFail($id);
        // $customer->update($request);
        return $customer;
    }

    /**
     * Validate customer data.
     *
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateData(array $data): array
    {
        $validator = Validator::make($data, [
            'id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}