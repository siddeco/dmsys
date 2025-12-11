<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // عرض جميع المستخدمين
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    // صفحة إنشاء مستخدم جديد
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // حفظ المستخدم
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')
                         ->with('success', 'User created successfully.');
    }

    // صفحة تعديل مستخدم
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // تحديث المستخدم
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string',
            'email' => "required|email|unique:users,email,$id",
            'role'  => 'required|exists:roles,name',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully.');
    }

    // حذف المستخدم
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index')
                         ->with('success', 'User deleted.');
    }
}
