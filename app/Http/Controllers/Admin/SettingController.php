<?php
// app\Http\Controllers\Admin\SettingController.php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function viewUsers()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        // Fetch non-admin roles
        $roles = Role::whereNotIn('name', ['admin'])->get();
    
        return view('admin\view_users', compact('users', 'roles'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $defaultPassword = env('DEFAULT_USER_PASSWORD', 'default_password');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($defaultPassword),
        ]);

        return redirect()->route('admin.view-users')->with('success', 'User added successfully.');
    }
    
    public function resetUserPassword($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->route('admin.view-users')->with('error', 'User not found.');
        }

        $user->update([
            'password' => bcrypt(env('DEFAULT_USER_PASSWORD', 'default_password')),
        ]);

        $user->save();

        return redirect()->route('admin.view-users')->with('success', 'User password reset successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.view-users')->with('error', 'User not found.');
        }

        $user->delete();

        return redirect()->route('admin.view-users')->with('success', 'User deleted successfully.');
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the roles
        $request->validate([
            'roles' => ['array', Rule::in(['jamut', 'prodi', 'gkm', 'auditor'])],
        ]);

        // Sync the roles for the user
        $user->syncRoles($request->input('roles', []));

        return redirect()->back()->with('success', 'Roles updated successfully.');
    }
}
