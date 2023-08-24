<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BaseController extends Controller
{
    /**
     * Handles user login.
     */
    public function loginUser($isAdmin, UserLoginRequest $request)
    {
        // Fetch the user based on the provided email
        $user = User::where('is_admin', $isAdmin)->where('email', $request->input('email'))->first();

        // Verify password and existence of user
        if ( ! $user || ! password_verify($request->input('password'), $user->password)) {
            return apiResponse(null, 'Invalid credentials', 401, false);
        }

        // Update last login timestamp
        $user->last_login_at = now();
        $user->save();

        // Generate and return JWT token
        $token = generateToken($user);

        return apiResponse(['user' => $user, 'token' => $token], 'User logged in successfully', 200, true);
    }

    /**
     * Handles user logout.
     */
    /**
     * @OA\Get(
     *     path="/api/v1/admin/logout",
     *     operationId="adminLogout",
     *     summary="Admin Logout",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Logged out successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="403", description="Forbidden", @OA\Schema()),
     * )
     * @OA\Get(
     *      path="/api/v1/user/logout",
     *      operationId="logoutUser",
     *      summary="Logout User",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response="200", description="Logged out successfully", @OA\Schema()),
     *      @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *  )
     */
    public function logout(Request $request)
    {
        // Extract token from Authorization header
        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        // Invalidate token by adding to blacklist
        revokeToken($token);

        return apiResponse(null, 'Logged out successfully', 200, true);
    }

    /**
     * Creates a new user
     */
    public function createUser($isAdmin, CreateUserRequest $request)
    {
        // Create a new User instance and fill it with request data
        $user = new User($request->all());

        // Set UUID and password for the user
        $user->uuid     = Str::uuid();
        $user->password = Hash::make($request->input('password'));

        // Mark the user as an admin
        $user->is_admin = $isAdmin;

        // Handle avatar file upload if present
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $uuid       = Str::uuid();
            $filename   = $uuid . '.' . $avatarFile->getClientOriginalExtension();
            $filePath   = 'pet-shop/' . $filename;
            // Store the avatar file
            Storage::disk('public')->putFileAs('', $avatarFile, $filePath);

            // create associated File record
            $file = File::create([
                'uuid' => $uuid,
                'name' => $avatarFile->getClientOriginalName(),
                'path' => $filePath,
                'size' => $avatarFile->getSize(),
                'type' => $avatarFile->getMimeType(),
            ]);

            // Associate the file with the user's avatar
            $user->avatar = $uuid;
        }

        // Save the user
        $user->save();

        // Return a success response
        return apiResponse(['user' => $user], 'Account created successfully', 201, true);
    }

    /**
     * Edits an existing user's details.
     */
    public function editUser(EditUserRequest $request, $uuid)
    {
        // Find the user by UUID or return a not found response
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return apiResponse(null, 'User not found', 404, false);
        }

        // Admins cannot be edited
        if ($user->is_admin) {
            return apiResponse(null, 'Admin accounts cannot be edited', 403, false);
        }

        // Update user data and save
        $currentAvatar = $user->avatar;
        $user->fill($request->all());
        $user->password = Hash::make($request->input('password'));

        // Handle avatar file upload and update if present
        if ($request->hasFile('avatar')) {
            // Delete existing avatar if present

            if ($user->avatar) {
                $file = File::where('uuid', $currentAvatar)->first();
                if ($file) {
                    Storage::delete('public/' . $file->path);
                    $file->delete();
                }
            }

            // Upload new avatar file and create associated File record
            $avatarFile = $request->file('avatar');
            $uuid       = Str::uuid();
            $filename   = $uuid . '.' . $avatarFile->getClientOriginalExtension();
            $filePath   = 'pet-shop/' . $filename;

            // Store the avatar file
            Storage::disk('public')->putFileAs('', $avatarFile, $filePath);

            // create associated File record
            $file = File::create([
                'uuid' => $uuid,
                'name' => $avatarFile->getClientOriginalName(),
                'path' => $filePath,
                'size' => $avatarFile->getSize(),
                'type' => $avatarFile->getMimeType(),
            ]);

            // Associate the file with the user's avatar
            $user->avatar = $uuid;
        }

        $user->save();

        // Return a success response
        return apiResponse(['user' => $user], 'User account edited successfully', 200, true);
    }

    /**
     * Deletes a user account.
     */
    public function deleteUser($uuid)
    {
        // Find the user by UUID or return a not found response
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return apiResponse(null, 'User not found', 404, false);
        }

        // Admins cannot be deleted
        if ($user->is_admin) {
            return apiResponse(null, 'Admin accounts cannot be deleted', 403, false);
        }

        // Delete the associated avatar file if present
        if ($user->avatar) {
            $file = File::where('uuid', $user->avatar)->first();
            if ($file) {
                Storage::delete('public/' . $file->path);
                $file->delete();
            }
        }

        // Delete the user
        $user->delete();

        // Return a success response
        return apiResponse(null, 'User account deleted successfully', 200, true);
    }
}
