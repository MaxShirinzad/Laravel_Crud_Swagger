<?php

namespace App\Http\Controllers\api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *   path="/products",
     *   tags={"Products"},
     *   @OA\Response(response="200",
     *   description="Products Collection",
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     description="Pagination page",
     *     example="1",
     *     in="query",
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->orderBy('id', 'desc')->paginate(10));
    }

    /**
     * @OA\Post(
     *   path="/products",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   summary="Create product",
     *   description="Create product",
     *   @OA\Response(response="201",
     *     description="Product Create",
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreProductRequest")
     *   )
     * )
     */
    public function store(StoreProductRequest $request): Application|Response|ResponseFactory
    {
        $data = $request->validated();
        $userID = auth('sanctum')->user()->id; // Auth::id();
        $data['user_id'] = $userID;
        //---------------------
        $product = Product::query()->create($data);

        return response(new ProductResource($product), 201);

    }

    /**
     * @OA\Get(
     *   path="/products/{slug}",
     *   tags={"Products"},
     *   description="Show product info by slug",
     *   @OA\Response(response="200",
     *     description="product",
     *   ),
     *   @OA\Parameter(
     *     name="slug",
     *     description="Product slug",
     *     in="path",
     *     required=true,
     *     example="mozsh-ton",
     *     @OA\Schema(
     *        type="string"
     *     )
     *   )
     * )
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }


    /**
     * @OA\Put(
     *   path="/products/{slug}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="202",
     *     description="Product Update",
     *   ),
     *   @OA\Parameter(
     *     name="slug",
     *     description="Product slug",
     *     in="path",
     *     required=true,
     *     example="product-title",
     *     @OA\Schema(
     *        type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateProductRequest")
     *   )
     * )
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $data = $request->validated();
        $product->update($data);
        return new ProductResource($product);
    }

    /**
     * @OA\Delete(path="/products/{slug}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="204",
     *     description="Product Delete",
     *   ),
     *   @OA\Parameter(
     *     name="slug",
     *     description="Product slug",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   )
     * )
     */
    public function destroy(Product $product): Application|Response|ResponseFactory
    {
        $product->delete();
        return response("", 204);
    }

}
