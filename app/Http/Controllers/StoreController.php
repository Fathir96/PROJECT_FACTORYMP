<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('keyword');
        $store = Store::orderBy('id', 'desc')
        ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        if($query){
            $store = Store::where('name', 'like', "%$query%")
            ->orWhere('phone_number', 'like', "%$query%")
            ->orWhere('address', 'like', "%$query%")
            ->orderBy('id', 'desc')
            ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        };
        return response()->json([
            'status' => 'success',
            'store' => $store
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone_number' => 'required|max:30|numeric',
            'address' => 'required|string|max:30'
        ], [
            'name.required' => 'Please fill the name field.',
            'phone_number.required' => 'Please fill the phone field.',
            'address.required' => 'Please fill the address field.'
        ]);
        $store = Store::create($validatedData);
        if ($store) {
            return response()->json([
                'status' => 'success',
                'message' => 'Store created',
                'store' => $store
            ]);
        }else {
            return response()->json([
                'status' => 'error',
                'message' => 'Store failed to create'
            ], 400);
        }
    }

    public function show(string $id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }else {
            return response()->json([
                'status' => 'success',
                'store' => $store
            ]);
        }
    }
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|max:255|numeric',
            'address' => 'required|string|max:255'
        ], [
            'name.required' => 'Please fill the name field.',
            'phone_number.required' => 'Please fill the phone field.',
            'address.required' => 'Please fill the address field.'
        ]);
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }else {
            $store->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Store updated',
                'store' => $store
            ]);
        }
    }

    public function destroy(string $id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }else {
            $store->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Store deleted',
                'store' => $store
            ]);
        }
    }
}
