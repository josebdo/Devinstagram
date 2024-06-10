<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    //
    public function index() {
        return view('auth.login');
    }

    public function store(Request $request) {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        

        if(!auth()->attempt($request->only('email', 'password'), $request->remember)) {
                return back()->with('mensaje', 'Credenciales incorrecta');
        }

        return redirect()->route('post.index', [$request->user()->username]);
    }
}
