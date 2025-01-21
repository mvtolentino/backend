<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index(Request $request){
        $per_page = isset($request->per_page) ? $request->per_page : 10;

        $customers = Customer::where('is_deleted', 0)->paginate($per_page);

        return response()->json([
            'data' => $customers,
            'message' => 'Customers fetch successfully'
        ]);
    }

    public function createCustomers(Request $request){
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:customers,email',
            'contact' => 'required|string'
        ]);

        Customer::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'contact' => $validatedData['contact'],
        ]);

        return response()->json([
            'message' => 'Customer created successfully',
        ], 201);
    }

    public function updateCustomers(Request $request){
        $validatedData = $request->validate([
            'id' => 'required|exists:customer,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:customers,email,' . $request->id, 
            'contact' => 'required|string'
        ]);
    
        $customer = Customer::findOrFail($validatedData['id']);
    
        $customer->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'contact' => $validatedData['contact'],
        ]);

        return response()->json([
            'message' => 'Customer updated successfully',
        ], 201);
    }

    public function deleteCustomers(Request $request){
        $validatedData = $request->validate([
            'id' => 'required|exists:customer,id',
            'is_deleted' => 'required',
        ]);
    
        $customer = Customer::findOrFail($validatedData['id']);

        $customer->update([
            'is_deleted' => $validatedData['is_deleted'],
        ]);

        return response()->json([
            'message' => 'Customer deleted successfully',
        ], 201);
    }
}
