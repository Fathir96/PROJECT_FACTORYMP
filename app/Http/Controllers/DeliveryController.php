<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Delivery",
 *     required={"order_type", "extra_protection", "shipping_price"},
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="order_type", type="string", example="Express"),
 *     @OA\Property(property="extra_protection", type="boolean", example=true),
 *     @OA\Property(property="shipping_price", type="number", format="float", example=9.99),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class DeliveryController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/deliveries",
     *     summary="Get a list of deliveries",
     * security={{"bearerAuth":{}}},
     *     description="Retrieve a paginated list of deliveries. You can search by order_type, extra_protection, or shipping_price.",
     *     operationId="getDeliveries",
     *     tags={"Deliveries"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="deliveries", type="array",
     *                 @OA\Items(ref="#/components/schemas/Delivery")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('keyword');
        if ($query) {
            $deliveries = Delivery::where('order_type', 'like', "%$query%")
                ->orWhere('extra_protection', 'like', "%$query%")
                ->orWhere('shipping_price', 'like', "%$query%")
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        } else {
            $deliveries = Delivery::paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        }

        return response()->json([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/deliveries",
     *     summary="Create a new delivery",
     * security={{"bearerAuth":{}}},
     *     description="Create a new delivery with the specified details.",
     *     operationId="createDelivery",
     *     tags={"Deliveries"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Delivery")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Delivery created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Delivery")
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_type' => 'required|string|max:255',
            'extra_protection' => 'required|boolean',
            'shipping_price' => 'required|numeric',
        ]);

        $delivery = Delivery::create($request->all());
        return response()->json($delivery, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/deliveries/{id}",
     *     summary="Get a delivery by ID",
     * security={{"bearerAuth":{}}},
     *     description="Retrieve a single delivery by its ID.",
     *     operationId="getDeliveryById",
     *     tags={"Deliveries"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Delivery ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Delivery")
     *     ),
     *     @OA\Response(response=404, description="Delivery not found")
     * )
     */
    public function show($id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        return response()->json($delivery);
    }

    /**
     * @OA\Put(
     *     path="/api/deliveries/{id}",
     *     summary="Update an existing delivery",
     * security={{"bearerAuth":{}}},
     *     description="Update the details of an existing delivery by its ID.",
     *     operationId="updateDelivery",
     *     tags={"Deliveries"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Delivery ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Delivery")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delivery updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Delivery")
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Delivery not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        $request->validate([
            'order_type' => 'required|string|max:255',
            'extra_protection' => 'required|boolean',
            'shipping_price' => 'required|numeric',
        ]);

        $delivery->update($request->all());
        return response()->json($delivery);
    }

    /**
     * @OA\Delete(
     *     path="/api/deliveries/{id}",
     *     summary="Delete a delivery",
     * security={{"bearerAuth":{}}},
     *     description="Delete an existing delivery by its ID.",
     *     operationId="deleteDelivery",
     *     tags={"Deliveries"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Delivery ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delivery deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Delivery deleted successfully"))
     *     ),
     *     @OA\Response(response=404, description="Delivery not found")
     * )
     */
    public function destroy($id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        $delivery->delete();
        return response()->json(['message' => 'Delivery deleted successfully']);
    }
}
