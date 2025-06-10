<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\ProductImages\StoreProductImageRequest;
use App\Http\Requests\ProductImages\UpdateProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\ProductImage;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductImageController extends Controller
{
    /**
     * @OA\Get(
     *   path="/productImages",
     *   tags={"ProductImages"},
     *   @OA\Response(response="200",
     *   description="ProductImages Collection",
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
        return ProductImageResource::collection(ProductImage::query()->orderBy('id', 'desc')->paginate(10));
    }

    /**
     * @OA\Post(
     *   path="/productImages",
     *   security={{"bearerAuth":{}}},
     *   tags={"ProductImages"},
     *   summary="Create productImage",
     *   description="Create productImage",
     *   @OA\Response(response="201",
     *     description="ProductImage Create",
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreProductImageRequest")
     *   )
     * )
     */
    public function store(StoreProductImageRequest $request): Application|Response|ResponseFactory
    {
        $data = $request->validated();
        $productImage = ProductImage::query()->create($data);

        return response(new ProductImageResource($productImage), 201);

    }

    /**
     * @OA\Get(
     *   path="/productImages/{id}",
     *   tags={"ProductImages"},
     *   description="Show productImage info by id",
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="ProductImage ID",
     *     in="path",
     *     required=true,
     *     example="1",
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function show(ProductImage $productImage): ProductImageResource
    {
        return new ProductImageResource($productImage);
    }


    /**
     * @OA\Put(
     *   path="/productImages/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"ProductImages"},
     *   @OA\Response(response="202",
     *     description="ProductImage Update",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="ProductImage ID",
     *     in="path",
     *     required=true,
     *     example="1",
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateProductImageRequest")
     *   )
     * )
     */
    public function update(UpdateProductImageRequest $request, ProductImage $productImage): ProductImageResource
    {
        $data = $request->validated();
        //----------------------------
        $productImage->update($data);
        return new ProductImageResource($productImage);
    }

    /**
     * @OA\Delete(path="/productImages/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"ProductImages"},
     *   @OA\Response(response="204",
     *     description="ProductImage Delete",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="ProductImage ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function destroy(ProductImage $productImage): Application|Response|ResponseFactory
    {
        $productImage->delete();
        return response("", 204);
    }
}
