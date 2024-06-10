<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index() {
        
        
        return view('perfil.index');
    }

    public function store(Request $request) {
        
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username,'.auth()->user()->id, 'min:3', 'max:20', 'not_in:twitter,editar-perfil']
        ]);

        if($request->imagen) {
            $imagen =$request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension();
     
            $manager = new ImageManager(new Driver());
     
            $imagenServidor = $manager::gd()->read($imagen);
    
            $imagenServidor->cover(1000,1000);
     
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
     
            $imagenServidor->save($imagenPath);
        } 

        //guardar cambios
        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;

        $usuario->save();

        //redireciconar al usuario
        return redirect()->route('post.index', $usuario->username);
    }
}
