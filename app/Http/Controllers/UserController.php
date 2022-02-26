<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUsers()
    {
        return response()->json([
            'message' => 'dale?'
        ], 201);
    }

    public function createUser(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->cpf = $request->cpf;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->note = $request->note;

        $user->save();

        return response()->json([
            'message' => 'user record created'
        ], 201);
    }

    public function getUser($id)
    {
        // logic to get a User record goes here
    }

    public function updateUser(Request $request, $id)
    {
        // logic to update a User record goes here
    }

    public function deleteUser($id)
    {
        // logic to delete a User record goes here
    }
}
