<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ToolsTraits;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for User Management"
 * )
 */
class UserController extends Controller
{
    use ToolsTraits;
    public string $imagePath = '';

    public function __construct()
    {
        //$this->imagePath = $_SERVER['DOCUMENT_ROOT'] . "users/images/";
        $this->imagePath = "users/images/";
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="List all users",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::query()->orderBy('id', 'desc')->paginate(50));
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        //---------------------
        // Check if image was given and save on local file system
        if (isset($validated['image'])) {
            $imageName = $this->saveImage($validated['image'], $this->imagePath);
            $validated['image'] = $imageName;
        }
        //----------------------------
        $user = User::query()->create($validated);

        return new UserResource($user);
    }

    /**
     * @OA\Get(path="/users/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Users"},
     *   description="Show user info by id",
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     example="1",
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }


    /**
     * @OA\Put(
     *   path="/users/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Users"},
     *   @OA\Response(response="202",
     *     description="User Update",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     example="21",
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *   )
     * )
     */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        //----------------------------
        // Check if image was given and save on local file system
        if (isset($data['image'])) {
            $imageName = $this->saveImage($data['image'], $this->imagePath);
            $data['image'] = $imageName;

            $this->deletePhotoOldPhoto($user->image, $this->imagePath);
        }
        //----------------------------
        $user->update($data);

        return new UserResource($user);
    }

    /**
     * @OA\Delete(path="/users/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Users"},
     *   @OA\Response(response="204",
     *     description="User Delete",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function destroy(User $user): Application|Response|ResponseFactory
    {
        $user->delete();
        $this->deletePhotoOldPhoto($user->image, $this->imagePath);
        return response("", 204);
    }




}
