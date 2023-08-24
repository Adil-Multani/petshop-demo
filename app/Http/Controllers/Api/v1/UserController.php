<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends BaseController
{
    /**
     * Handles user login.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     operationId="userLogin",
     *     summary="User Login",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="userpassword"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="User logged in successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="422", description="Validation error", @OA\Schema()),
     * )
     */
    public function login(UserLoginRequest $request)
    {
        return $this->loginUser(false, $request);
    }

    /**
     * Creates a new user
     */
    /**
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     operationId="createUser",
     *     summary="Create User",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"first_name", "last_name", "email", "password", "password_confirmation", "address",
     *                 "phone_number"},
     *                 @OA\Property(property="first_name", type="string", example="test"),
     *                 @OA\Property(property="last_name", type="string", example="user"),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="userpassword"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password",
     *                                                                example="userpassword"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="phone_number", type="string", example="123-456-7890"),
     *                 @OA\Property(property="avatar", type="file", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Account created successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="422", description="Validation error", @OA\Schema()),
     * )
     */
    public function create(CreateUserRequest $request)
    {
        return $this->createUser(false, $request);
    }

    /**
     * Edits current user's details.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/user/edit",
     *     operationId="editUser",
     *     summary="Edit User",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="first_name", type="string", example="updated_first_name"),
     *                 @OA\Property(property="last_name", type="string", example="updated_last_name"),
     *                 @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="userpassword"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password",
     *                                                                example="userpassword"),
     *                 @OA\Property(property="address", type="string", example="Updated Address"),
     *                 @OA\Property(property="phone_number", type="string", example="123-456-7890"),
     *                 @OA\Property(property="avatar", type="file", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="User account edited successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="403", description="Forbidden", @OA\Schema()),
     *     @OA\Response(response="404", description="User not found", @OA\Schema()),
     *     @OA\Response(response="422", description="Validation error", @OA\Schema()),
     * )
     */
    public function edit(EditUserRequest $request)
    {
        return $this->editUser($request, $request->user_uuid);
    }

    /**
     * Deletes current user account.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     operationId="userDetails",
     *     summary="User Details",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="User details", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="404", description="User not found", @OA\Schema()),
     * )
     */
    public function userDetails(Request $request)
    {
        // Find the user by UUID or return a not found response
        try {
            $user = User::where('uuid', $request->user_uuid)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return apiResponse(null, 'User not found', 404, false);
        }

        return apiResponse($user, 'User details', 200, true);
    }

    /**
     * provides current user details.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     operationId="userForgotPassword",
     *     summary="Forgot Password",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email"},
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Password reset token", @OA\Schema()),
     *     @OA\Response(response="404", description="User not found", @OA\Schema()),
     *     @OA\Response(response="422", description="Validation error", @OA\Schema()),
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return apiResponse(null, 'User not found', 404, false);
        }
        // Generate a unique token
        $token = Str::random(60);
        // Store the token in the password_resets table
        PasswordReset::updateOrInsert(['email' => $user->email], ['token' => $token, 'created_at' => now()]);

        return apiResponse(['token' => $token], 'Password reset token', 200, true);
    }

    /**
     * Generates password reset token.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/user/reset-password-token",
     *     operationId="userResetPasswordToken",
     *     summary="Reset Password with Token",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"token", "email", "password", "password_confirmation"},
     *                 @OA\Property(property="token", type="string", example="reset_token"),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="userpassword"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password",
     *                                                                example="userpassword"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Password has been successfully updated", @OA\Schema()),
     *     @OA\Response(response="400", description="Invalid token", @OA\Schema()),
     *     @OA\Response(response="404", description="User not found", @OA\Schema()),
     *     @OA\Response(response="422", description="Validation error", @OA\Schema()),
     * )
     */
    public function resetPasswordToken(ResetPasswordRequest $request)
    {
        try {
            $resetData = PasswordReset::where('email', $request->email)->where('token', $request->token)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return apiResponse(null, 'Invalid token', 400, false);
        }

        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return apiResponse(null, 'User not found', 404, false);
        }

        // Update user's password
        $user->update(['password' => Hash::make($request->password)]);

        // Delete the used token
        PasswordReset::where('email', $request->email)->delete();

        return apiResponse(null, 'Password has been successfully updated', 200, true);
    }

    /**
     * Handles password reset.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     operationId="deleteUser",
     *     summary="Delete User",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="User account deleted successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="403", description="Forbidden", @OA\Schema()),
     *     @OA\Response(response="404", description="User not found", @OA\Schema()),
     * )
     */
    public function delete(Request $request)
    {
        return $this->deleteUser($request->user_uuid);
    }
}
