<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints for Orders"
 * )
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     * security={{"bearerAuth":{}}},
     *     summary="Get list of orders",
     *     description="Returns list of orders",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     * )
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     * security={{"bearerAuth":{}}},
     *     summary="Create a new order",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_date", "user_id", "product_id", "destination_address"},
     *             @OA\Property(property="order_date", type="string", format="date", example="2023-09-18"),
     *             @OA\Property(property="description", type="string", example="Order description"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="voucher_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="payment_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="delivery_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="destination_address", type="string", example="1234 Main St, City, Country"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_date' => 'required|date',
            'description' => 'nullable|string',
            'user_id' => 'required|integer', // Removed 'exists' constraint
            'product_id' => 'required|integer', // Removed 'exists' constraint
            'voucher_id' => 'nullable|integer', // Removed 'exists' constraint
            'payment_id' => 'nullable|integer', // Removed 'exists' constraint
            'delivery_id' => 'nullable|integer', // Removed 'exists' constraint
            'destination_address' => 'required|string',
        ]);

        $order = Order::create($request->all());
        return response()->json($order, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     * security={{"bearerAuth":{}}},
     *     summary="Get an order by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order found",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     * security={{"bearerAuth":{}}},
     *     summary="Update an existing order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_date", "user_id", "product_id", "destination_address"},
     *             @OA\Property(property="order_date", type="string", format="date", example="2023-09-18"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="voucher_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="payment_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="delivery_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="destination_address", type="string", example="1234 Main St, City, Country"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'order_date' => 'required|date',
            'description' => 'nullable|string',
            'user_id' => 'required|integer', // Removed 'exists' constraint
            'product_id' => 'required|integer', // Removed 'exists' constraint
            'voucher_id' => 'nullable|integer', // Removed 'exists' constraint
            'payment_id' => 'nullable|integer', // Removed 'exists' constraint
            'delivery_id' => 'nullable|integer', // Removed 'exists' constraint
            'destination_address' => 'required|string',
        ]);

        $order->update($request->all());
        return response()->json($order);
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     * security={{"bearerAuth":{}}},
     *     summary="Delete an order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
