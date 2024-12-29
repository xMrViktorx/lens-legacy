<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Renderable;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     * @return Renderable
     */
    public function index()
    {
        $users = User::all();
        return view('admin::user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::user.create');
    }

    /**
     * Store a newly created user in the database.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $validated['password'] = Hash::make('password');

        User::create($validated);

        return redirect()->route('admin.user.index')->with('success', 'Felhasználó sikeresen létrehozva!');
    }

    /**
     * Show the form for editing an existing user.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin::user.edit', compact('user'));
    }

    /**
     * Update an existing user in the database.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
        ];

        // Add password validation rules only if password is filled in the request
        if ($request->filled('password')) {
            $rules['password'] = 'required|confirmed';
        }

        $validated = $request->validate($rules);

        // Update password only if it's filled in the request
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Felhasználó sikeresen frissítve!');
    }

    /**
     * Delete a user from the database.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();
        
        return redirect()->back()->with('success', 'Felhasználó sikeresen törölve!');
    }
}