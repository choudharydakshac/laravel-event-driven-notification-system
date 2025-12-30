<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Trigger user registration event
     */
    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create($data);

        event(new UserRegistered($user));

        return response()->json([
            'message' => 'User registered and notification triggered',
            'user_id' => $user->id,
        ], 201);
    }

    /**
     * List notification logs
     */
    public function index()
    {
        return NotificationLog::query()
            ->latest()
            ->paginate(20);
    }
}
