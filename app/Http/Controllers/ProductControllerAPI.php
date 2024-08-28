<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Marketplace API",
 *      description="Use this API to manage product data.",
 *      @OA\Contact(
 *          email="fathir1234@gmail.com"
 *      )
 * )
 * 
 * @OA\SecurityScheme(
 *    type="http",
 *    scheme="bearer",
 *    securityScheme="bearerAuth"
 * )
 * 
 * 
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="ProductsAPI"
 * )
 * 
 * 
 * @OA\Schema(
 *     schema="Product",
 *     required={"id", "name", "price", "stock"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Product 1"),
 *     @OA\Property(property="price", type="number", example=10000),
 *     @OA\Property(property="stock", type="integer", example=10),
 *      @OA\Property(property="category_id", type="integer", example=1),
 *    @OA\Property(property="brand_id", type="integer", example=1),
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="password", type="string", example="password123"),
 * )
 * 
 */

 

class ProductControllerAPI extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/Product")),
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('keyword');
        $products = Product::orderBy('price', 'desc');

        if ($query) {
            $products = $products->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%$query%")
                    ->orWhere('price', 'like', "%$query%")
                    ->orWhere('stock', 'like', "%$query%");
            });
        }

        $category = $request->input('category');
        if ($category) {
            $products->whereHas('category', function ($query) use ($category) {
                $query->where('category_name', $category);
            });
        }

        $brand = $request->input('brand');
        if ($brand) {
            $products->whereHas('brand', function ($query) use ($brand) {
                $query->where('brand_name', $brand);
            });
        }

        $products = $products->paginate(10);
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category ? $product->category->category_name : null,
                'brand' => $product->brand ? $product->brand->brand_name : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved',
            'products' => $products
        ]);
    }

   // create a swegger anotation for the store method with bearerAuth security scheme
   /**
    * @OA\Post(
    *     path="/api/products",
    *     tags={"Products"},
    *     security={{"bearerAuth":{}}},
    *     @OA\RequestBody(
    *         description="Product data",
    *         required=true,
    *         @OA\JsonContent(ref="#/components/schemas/Product")
    *     ),
    *     @OA\Response(
    *          response=201,
    *          description="success",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="string", example="success"),
    *              @OA\Property(property="product", type="object", ref="#/components/schemas/Product"),
    *          )
    *      )
    * )
    */
   
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' =>'required|string|max:255',
            'price' =>'required|numeric',
           'stock' =>'required|integer',
           'category_id' =>'required|integer',
           'brand_id' =>'required|integer',
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.',
            'category_id.required' => 'The category_id field is required.',
            'brand_id.required' => 'The brand_id field is required.',
            'stock.integer' => 'The stock field must be an integer.',
            'category_id.integer' => 'The category_id field must be an integer.',
            'brand_id.integer' => 'The brand_id field must be an integer.',
        ]);
        $products = Product::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Product created',
            'product' => $products
        ], 201);
    }

    
    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get a specific product",
     *      security={{"bearerAuth":{}}},
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="product", type="object", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $products = Product::find($id);
        if (!$products) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }else {
            return response()->json([
                'status' => 'success',
                'product' => $products
            ]);
        }
      
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product updated"),
     *             @OA\Property(property="product", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="stock", type="integer"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="brand_id", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'name' =>'required|max:255',
            'price' =>'required|numeric',
           'stock' =>'required|integer',
           'brand_id' =>'required|integer',
           'category_id' =>'required|integer',
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.',
            'name.string' => 'The name field must be a string.',
            'price.numeric' => 'The price field must be a number.',
            'stock.integer' => 'The stock field must be an integer.',
            'category_id.required' => 'The category_id field is required.',
            'brand_id.required' => 'The brand_id field is required.',
            'category_id.integer' => 'The category_id field must be an integer.',
            'brand_id.integer' => 'The brand_id field must be an integer.'  
        ]);


        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }else {

            $product->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated',

                'product' => $product
            ]);
            
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Produk tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message'=> 'Produk tidak ditemukan'],404);
    }

    $product->delete();
    return response()->json(['message' => 'Produk berhasil dihapus']);

    }
}
