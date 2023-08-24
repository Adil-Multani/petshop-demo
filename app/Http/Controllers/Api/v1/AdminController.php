<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserListingRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;

/**
 * @OA\Info(
 *    title="Pet Shop API",
 *    version="1.0",
 *    description="API documentation for the Pet Shop application.",
 *  )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
class AdminController extends BaseController
{
    /**
     * Handles user login.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/admin/login",
     *     operationId="adminLogin",
     *     summary="Admin Login",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(property="email", type="string", format="email", example="admin@buckhill.co.uk"),
     *                 @OA\Property(property="password", type="string", format="password", example="admin"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful login", @OA\Schema()),
     *     @OA\Response(response="401", description="Invalid credentials", @OA\Schema()),
     *     @OA\Response(response="400", description="Validation error", @OA\Schema()),
     *     @OA\Response(response="403", description="Unauthorized", @OA\Schema()),
     * )
     */
    public function login(UserLoginRequest $request)
    {
        return $this->loginUser(true, $request);
    }

    /**
     * Creates a new admin user
     */
    /**
     * @OA\Post(
     *     path="/api/v1/admin/create",
     *     operationId="adminCreateUser",
     *     summary="Create User (Admin)",
     *     tags={"Admin"},
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
     *                 @OA\Property(property="email", type="string", format="email", example="test@yopmail.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="admin123"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password",
     *                                                                example="admin123"),
     *                 @OA\Property(property="address", type="string", example="test"),
     *                 @OA\Property(property="phone_number", type="string", example="141414141414141"),
     *                 @OA\Property(property="is_marketing", type="integer", format="int32", example=0),
     *                 @OA\Property(property="avatar", type="file", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Account created successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="403", description="Forbidden", @OA\Schema()),
     *     @OA\Response(response="422", description="Validation error", @OA\Schema()),
     * )
     */
    public function create(CreateUserRequest $request)
    {
        return $this->createUser(true, $request);
    }

    /**
     * Edits an existing user's details.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/admin/user-edit/{uuid}",
     *     operationId="adminEditUser",
     *     summary="Edit User (non-Admin)",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the user to edit",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="first_name", type="string", example="test"),
     *                 @OA\Property(property="last_name", type="string", example="user"),
     *                 @OA\Property(property="email", type="string", format="email", example="test@yopmail.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="admin123"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password",
     *                                                                example="admin123"),
     *                 @OA\Property(property="address", type="string", example="test"),
     *                 @OA\Property(property="phone_number", type="string", example="141414141414141"),
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
    public function edit(EditUserRequest $request, $uuid)
    {
        return $this->editUser($request, $uuid);
    }

    /**
     * Deletes a user account.
     */
    /**
     * @OA\Delete(
     *     path="/api/v1/admin/user-delete/{uuid}",
     *     operationId="adminDeleteUser",
     *     summary="Delete User (non-Admin)",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the user to delete",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response="200", description="User account deleted successfully", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     *     @OA\Response(response="403", description="Forbidden", @OA\Schema()),
     *     @OA\Response(response="404", description="User not found", @OA\Schema()),
     * )
     */
    public function delete($uuid)
    {
        return $this->deleteUser($uuid);
    }

    /**
     * Provides user list
     */
    /**
     * @OA\Get(
     *     path="/api/v1/admin/user-listing",
     *     operationId="adminUserListing",
     *     summary="User Listing (non-Admin)",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination (default: 1)",
     *         @OA\Schema(type="integer", minimum=1),
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of items per page (default: 10)",
     *         @OA\Schema(type="integer", minimum=1),
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Column to sort by (default: created_at)",
     *         @OA\Schema(type="string", enum={"created_at", "last_login_at", "first_name", "is_marketing", "email"}),
     *     ),
     *     @OA\Parameter(
     *         name="desc",
     *         in="query",
     *         description="Sort in descending order (default: false)",
     *         @OA\Schema(type="boolean"),
     *     ),
     *     @OA\Parameter(
     *         name="is_marketing",
     *         in="query",
     *         description="Filter by marketing status (default: false)",
     *         @OA\Schema(type="boolean"),
     *     ),
     *     @OA\Response(response="200", description="Users list", @OA\Schema()),
     *     @OA\Response(response="401", description="Unauthorized", @OA\Schema()),
     * )
     */
    public function list(UserListingRequest $request)
    {
        // Retrieve listing parameters
        $page        = $request->input('page', 1);
        $limit       = $request->input('limit', 10);
        $sortBy      = $request->input('sort_by', 'created_at');
        $desc        = $request->input('desc', false); // Set to false by default
        $isMarketing = $request->input('is_marketing', false); // Set to false by default

        // Build query for user listing
        $query = User::query()->where('is_admin', 0);

        // If is_marketing parameter is provided, filter by it
        if ($isMarketing !== false) {
            $query->where('is_marketing', $isMarketing);
        }

        // Determine sorting direction
        $orderBy = $desc ? 'desc' : 'asc';

        // Validate and sanitize the sorting column
        $allowedSortColumns = ['created_at', 'last_login_at', 'first_name', 'is_marketing', 'email'];
        $sortBy             = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';

        // Fetch paginated users with the specified sorting and limiting
        $users = $query->orderBy($sortBy, $orderBy)->paginate($limit, ['*'], 'page', $page);

        // Return the paginated users as a response
        return apiResponse($users, 'Users list', 200, true);
    }
}
