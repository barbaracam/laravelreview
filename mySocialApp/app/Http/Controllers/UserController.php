<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:15', Rule::unique('users', 'username') ],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            // confirmed check match password
            'password' => ['required','min:6', 'confirmed'],
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        User::create($incomingFields);
        return 'hello';
    }
}
