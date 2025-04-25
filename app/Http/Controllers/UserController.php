<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'msg' => 'Users listed successfully',
            'usersCount' => $users->count(),
            'users' => $users
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                ],
                [
                    'name.required' => 'Field name is required',
                    'email.required' => 'Field email is required',
                    'password.required' => 'Field password is required',
                ]
            );

            $user = User::create($request->all());
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Failed to register user',
                'error' => $error->getMessage()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'msg' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'msg' => 'User not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'msg' => 'User found successfully',
            'user' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                ],
                [
                    'name.required' => 'Field name is required',
                    'email.required' => 'Field email is required',
                    'password.required' => 'Field password is required',
                ]
            );

            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;

            $user->save();

            return response()->json([
                'success' => true,
                'msg' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Failed to update user',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'success' => true,
                'msg' => "User $user->name deleted successfully",
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Failed to delete user',
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
