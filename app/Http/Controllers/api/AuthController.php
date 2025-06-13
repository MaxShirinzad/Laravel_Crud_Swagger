<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Users\LoginRequest;
use App\Http\Requests\Users\SignupRequest;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/signup",
     *     operationId="authSignup",
     *     tags={"Register & Login"},
     *     summary="Register new user",
     *     description="Creates a new user account and returns an API token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="aa123123",
     *                 minLength=6
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 format="password",
     *                 example="aa123123"
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="access_token", type="string", example="1|XyZ123..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=525600),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     security={}
     * )
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $expireDays = 7;
        $token = $user->createToken('auth_token', ['*'], now()->addDays($expireDays))->plainTextToken;

        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 'In ' . $expireDays . ' days',
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     operationId="authLogin",
     *     tags={"Register & Login"},
     *     summary="Authenticate user and generate API token",
     *     description="Logs in a user and returns a Sanctum API token for authorization",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     example="user@site.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     example="1234567890",
     *                     minLength=6
     *                 ),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             ),
     *             @OA\Property(
     *                 property="access_token",
     *                 type="string",
     *                 example="1|XyZ123..."
     *             ),
     *             @OA\Property(
     *                 property="token_type",
     *                 type="string",
     *                 example="Bearer"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The provided credentials are incorrect.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=429,
     *         description="Too many login attempts",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Too many login attempts. Please try again later."
     *             )
     *         )
     *     ),
     *
     *     security={}
     * )
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            //return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke all previous tokens
        $user->tokens()->delete();

        // Create new token with abilities
        $expireDays = 7;
        $token = $user->createToken('auth_token', ['*'], now()->addDays($expireDays))->plainTextToken;

        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 'In ' . $expireDays . ' days',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     operationId="authLogout",
     *     tags={"Register & Login"},
     *     summary="Logout user",
     *     description="Revokes the current access token",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ], Response::HTTP_OK);
    }


}
