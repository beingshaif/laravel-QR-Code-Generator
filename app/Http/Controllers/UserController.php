<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('welcome', ['users' => $users]);
    }
    public function store(Request $request)
    {
        // Validating The Users
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10|numeric',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|between:8,255',
        ]);

        // Creating The users
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back();
    }
    public function generate($id)
    {
        $user = User::select('name','email','phone')->where('id', $id)->first();
        $qrcode = QrCode::size(400)->generate($user);
        return view('qrcode', compact('qrcode'));
    }
}
