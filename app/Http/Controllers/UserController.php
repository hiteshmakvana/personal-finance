<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:super_admin,admin,manager',
            'manager_capability' => 'nullable|in:income,expense,both',
        ]);
        if ($data['role'] !== 'manager') {
            $data['manager_capability'] = null;
        }
        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:super_admin,admin,manager',
            'manager_capability' => 'nullable|in:income,expense,both',
        ]);
        if ($data['role'] !== 'manager') {
            $data['manager_capability'] = null;
        }
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }
}
