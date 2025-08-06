<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['addresses', 'provider', 'kycVerifications'])->paginate(10);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password_hash' => 'required|string|min:8',
            'confirm_password' => 'required|same:password_hash',
            'role' => 'sometimes|in:admin,provider,user',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password_hash' => Hash::make($request->password_hash),
            'confirm_password' => Hash::make($request->confirm_password),
            'role' => $request->role ?? 'user',
        ]);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user->load(['addresses', 'provider', 'kycVerifications']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone_number' => 'sometimes|string|max:20|unique:users,phone_number,' . $user->user_id . ',user_id',
            'password_hash' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,provider,user',
            'account_status' => 'sometimes|in:pending_kyc,active,suspended,rejected',
            'profile_picture_url' => 'sometimes|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        if ($request->has('password_hash')) {
            $data['password_hash'] = Hash::make($request->password_hash);
            $data['confirm_password'] = Hash::make($request->password_hash);
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}