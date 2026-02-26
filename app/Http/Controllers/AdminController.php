<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
public function dashboard()
{
    // Fetch distinct departments for the dropdown
    $departments = User::where('status', 'active')
                       ->distinct()
                       ->pluck('department');

    // Pass departments to the dashboard view
    return view('admin.dashboard', compact('departments'));
}

    public function getInactiveUsers()
{
    // Fetch users with 'inactive' status
    $inactiveUsers = User::where('status', 'inactive')->get();

    // Return the list of inactive users as JSON
    return response()->json($inactiveUsers);
}

public function activateUser($id)
{
    $user = User::findOrFail($id);
    $user->status = 'active';
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User activated successfully.'
    ]);
}
}
