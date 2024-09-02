<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Payments",
 *     description="API Endpoints of Payments"
 * )
 */
class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="Get a list of payments",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a list of payments, optionally filtered by a keyword.",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword for filtering payments by method",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of payments",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="payments", type="array", @OA\Items(ref="#/components/schemas/Payment"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('keyword');
        if ($query) {
            $payments = Payment::where('method', 'like', "%$query%")
                ->orderBy('method', 'asc')
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        } else {
            $payments = Payment::orderBy('method', 'asc')->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        }

        return response()->json([
            'status' => 'success',
            'payments' => $payments
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="Create a new payment",
     *     security={{"bearerAuth":{}}},
     *     description="Creates a new payment record.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"method", "number_id"},
     *             @OA\Property(property="method", type="string", example="Credit Card"),
     *             @OA\Property(property="number_id", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string|max:255',
            'number_id' => 'required|string|max:255',
        ]);

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Get a specific payment",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a specific payment by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the payment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A specific payment",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json($payment);
    }

    /**
     * @OA\Put(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Update a specific payment",
     *     security={{"bearerAuth":{}}},
     *     description="Updates an existing payment record.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the payment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"method", "number_id"},
     *             @OA\Property(property="method", type="string", example="Credit Card"),
     *             @OA\Property(property="number_id", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment updated",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $request->validate([
            'method' => 'required|string|max:255',
            'number_id' => 'required|string|max:255',
        ]);

        $payment->update($request->all());
        return response()->json($payment);
    }

    /**
     * @OA\Delete(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Delete a specific payment",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a payment record by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the payment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
