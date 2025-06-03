<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ToolsTraits;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;


class UserController extends Controller
{
    use ToolsTraits;
    public string $imagePath = '';

    public function __construct()
    {
        //        $this->imagePath = public_path() . "/uploadimage/";
        //$this->imagePath = $_SERVER['DOCUMENT_ROOT'] . "/users/images/";
        //$this->imagePath = $_SERVER['DOCUMENT_ROOT'] . "users/images/";
        $this->imagePath = "users/images/";
    }

    /**
     * @OA\Get(
     *   path="/users",
     *   security={{"bearerAuth":{}}},
     *   tags={"Users"},
     *   @OA\Response(response="200",
     *   description="User Collection",
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
        return UserResource::collection(User::query()->orderBy('id', 'desc')->paginate(50));
    }

    /**
     * @OA\Post(
     *   path="/users",
     *   security={{"bearerAuth":{}}},
     *   tags={"Users"},
     *   summary="Create user",
     *   description="Create user",
     *   @OA\Response(response="201",
     *     description="User Create",
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *   )
     * )
     */
    public function store(StoreUserRequest $request): Application|Response|ResponseFactory
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);


//            $userID = $user->id;
//            $data['user_id'] = $userID;
            //---------------------
            // Check if image was given and save on local file system
            if (isset($data['image'])) {
                $imageName = $this->saveImage($data['image'], $this->imagePath);
                $data['image'] = $imageName;
            }
            //----------------------------
//            $post = new Post();
//            $post->query()->create($data);

            $user = User::query()->create($data);

            return response(new UserResource($user), 201);
        } catch (\Exception $exception) {
            return response($exception->getMessage(), 409);
        }
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
