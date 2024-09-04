<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/stores",
     *     operationId="getStoresList",
     *     tags={"Stores"},
     *     summary="Get list of stores",
     * security={{"bearerAuth":{}}},
     *     description="Returns list of stores",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword for searching stores",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="store",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Stores not found"
     *     )
     * )
     */    public function index(Request $request)
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

    /**
     * @OA\Post(
     *     path="/api/stores",
     *     operationId="storeStore",
     *     tags={"Stores"},
     *     summary="Create a new store",
     * security={{"bearerAuth":{}}},
     *     description="Create a new store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone_number","address"},
     *             @OA\Property(property="name", type="string", example="Store Name"),
     *             @OA\Property(property="phone_number", type="string", example="123456789"),
     *             @OA\Property(property="address", type="string", example="123 Main St, City, Country")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Store created"),
     *             @OA\Property(
     *                 property="store",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Store Name"),
     *                 @OA\Property(property="phone_number", type="string", example="123456789"),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City, Country"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/stores/{id}",
     *     operationId="getStoreById",
     *     tags={"Stores"},
     *     summary="Get store information",
     * security={{"bearerAuth":{}}},
     *     description="Get store information by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="store",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Store Name"),
     *                 @OA\Property(property="phone_number", type="string", example="123456789"),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City, Country"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */    public function show(string $id)
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

    /**
     * @OA\Put(
     *     path="/api/stores/{id}",
     *     operationId="updateStore",
     *     tags={"Stores"},
     *     summary="Update an existing store",
     * security={{"bearerAuth":{}}},
     *     description="Update an existing store",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone_number","address"},
     *             @OA\Property(property="name", type="string", example="Updated Store Name"),
     *             @OA\Property(property="phone_number", type="string", example="987654321"),
     *             @OA\Property(property="address", type="string", example="456 Other St, City, Country")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Store updated"),
     *             @OA\Property(
     *                 property="store",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Store Name"),
     *                 @OA\Property(property="phone_number", type="string", example="987654321"),
     *                 @OA\Property(property="address", type="string", example="456 Other St, City, Country"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */    public function update(Request $request, string $id)
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

    /**
     * @OA\Delete(
     *     path="/api/stores/{id}",
     *     operationId="deleteStore",
     *     tags={"Stores"},
     *     summary="Delete a store",
     * security={{"bearerAuth":{}}},
     *     description="Delete a store by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Store deleted"),
     *           @OA\Property(
     *                 property="store",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Store Name"),
     *                 @OA\Property(property="phone_number", type="string", example="123456789"),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City, Country"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */    public function destroy(string $id)
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

