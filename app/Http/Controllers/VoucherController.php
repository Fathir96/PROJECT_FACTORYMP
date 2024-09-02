<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Voucher",  
 *     required={"discount_price", "expired_date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="discount_price", type="number", format="float", example=15.50),
 *     @OA\Property(property="expired_date", type="string", format="date", example="2024-12-31"),
 *     @OA\Property(property="desc", type="string", example="Special holiday discount"),
 * )
 */
class VoucherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/vouchers",
     *     security={{"bearerAuth":{}}},
     *     summary="Get list of vouchers",
     *     tags={"Vouchers"},   
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword to search for vouchers",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Voucher"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('keyword');
        if ($query) {
            $vouchers = Voucher::where('discount_price', 'like', "%$query%")
                ->orWhere('expired_date', 'like', "%$query%")
                ->orWhere('desc', 'like', "%$query%")
                ->get();
        } else {
            $vouchers = Voucher::all();
        }

        return response()->json($vouchers);
    }

    /**
     * @OA\Post(
     *     path="/api/vouchers",
     *     security={{"bearerAuth":{}}},
     *     tags={"Vouchers"},  
     *     summary="Create a new voucher",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Voucher")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Voucher created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Voucher")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'discount_price' => 'required|numeric',
            'expired_date' => 'required|date',
            'desc' => 'nullable|string|max:255',
        ]);

        $voucher = Voucher::create($request->all());
        return response()->json($voucher, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/vouchers/{id}",
     *     security={{"bearerAuth":{}}},
     *     tags={"Vouchers"},  
     *     summary="Get a specific voucher by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Voucher")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voucher not found"
     *     )
     * )
     */
    public function show($id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        return response()->json($voucher);
    }

    /**
     * @OA\Put(
     *     path="/api/vouchers/{id}",
     *     security={{"bearerAuth":{}}},  
     *     tags={"Vouchers"},   
     *     summary="Update a specific voucher by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Voucher")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voucher updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Voucher")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voucher not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $request->validate([
            'discount_price' => 'required|numeric',
            'expired_date' => 'required|date',
            'desc' => 'nullable|string|max:255',
        ]);

        $voucher->update($request->all());
        return response()->json($voucher);
    }

    /**
     * @OA\Delete(
     *     path="/api/vouchers/{id}",
     *     security={{"bearerAuth":{}}}, 
     *     tags={"Vouchers"},      
     *     summary="Delete a specific voucher by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voucher deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voucher not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucher->delete();
        return response()->json(['message' => 'Voucher deleted successfully']);
    }
}
