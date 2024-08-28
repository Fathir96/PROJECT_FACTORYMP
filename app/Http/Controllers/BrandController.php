<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Brand",
 *     required={"brand_name", "brand_address", "brand_email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="brand_name", type="string", example="Acme Corporation"),
 *     @OA\Property(property="brand_address", type="string", example="123 Main St, Springfield"),
 *     @OA\Property(property="brand_email", type="string", example="info@acme.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-26T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-26T00:00:00.000000Z"),
 * )
 */

class BrandController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/brands",
     *     summary="Get all brands",
     *     description="Retrieve a list of all brands, with optional filtering by keyword",
     *     security={{"bearerAuth":{}}},
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="A keyword to filter brands by their name"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of brands",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="brands", type="array", @OA\Items(ref="#/components/schemas/Brand"))
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        $query = $request->input('keyword');
        if ($query) {
            $brands = Brand::where('brand_name', 'like', "%$query%")
                ->orderBy('brand_name', 'asc')
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        } else {
            $brands = Brand::orderBy('brand_name', 'asc')->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        }

        return response()->json([
            'status' => 'success',
            'brands' => $brands
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/brands",
     *     summary="Create a new brand",
     *     description="Create a new brand with name, address, and email",
     *     tags={"Brands"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_name", "brand_address", "brand_email"},
     *             @OA\Property(property="brand_name", type="string", example="Acme Corporation"),
     *             @OA\Property(property="brand_address", type="string", example="123 Main St, Springfield"),
     *             @OA\Property(property="brand_email", type="string", example="info@acme.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Brand")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_address' => 'required|string|max:255',
            'brand_email' => 'required|email|unique:brands,brand_email',
        ]);

        $brand = Brand::create($request->all());
        return response()->json($brand, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/brands/{id}",
     *     summary="Get a specific brand",
     *     description="Retrieve a specific brand by its ID",
     *     security={{"bearerAuth":{}}},
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the brand"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Brand")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Brand not found")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return response()->json($brand);
    }
    /**
     * @OA\Put(
     *     path="/api/brands/{id}",
     *     summary="Update a specific brand",
     *     description="Update the name, address, and/or email of a specific brand by its ID",
     *     security={{"bearerAuth":{}}},
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the brand"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_name", "brand_address", "brand_email"},
     *             @OA\Property(property="brand_name", type="string", example="Acme Corporation"),
     *             @OA\Property(property="brand_address", type="string", example="123 Main St, Springfield"),
     *             @OA\Property(property="brand_email", type="string", example="info@acme.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Brand")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Brand not found")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_address' => 'required|string|max:255',
            'brand_email' => 'required|email|unique:brands,brand_email,' . $id,
        ]);

        $brand->update($request->all());
        return response()->json($brand);
    }

    /**
     * @OA\Delete(
     *     path="/api/brands/{id}",
     *     summary="Delete a specific brand",
     *     description="Delete a specific brand by its ID",
     *     security={{"bearerAuth":{}}},
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the brand"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Brand deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Brand not found")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
