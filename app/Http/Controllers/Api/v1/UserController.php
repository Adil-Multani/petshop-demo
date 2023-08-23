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
    public function login(UserLoginRequest $request)
    {
        return $this->loginUser(false, $request);
    }

    public function create(CreateUserRequest $request)
    {
        return $this->createUser(false, $request);
    }

    public function edit(EditUserRequest $request)
    {
        return $this->editUser($request, $request->user_uuid);
    }

    public function delete(Request $request)
    {
        return $this->deleteUser($request->user_uuid);
    }

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
}
