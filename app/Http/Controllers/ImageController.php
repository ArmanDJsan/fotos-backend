<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function index(){
        $user= Auth::user();
        $images= $user->images;
        return response()->json([$images],200);
    }
    public function store(Request $request)
    {
        $id= $request->id;
        $user= User::where('url',$id)->first();
        $password = $request->password;
        $images = $request->file('images');
        $request->validate([
            'images' => 'required|image|mimes:jpeg,png,bmp|max:2048|dimensions:min_width=100,min_height=100',
        ]);
    
        if ($user->images->count() >= 3) {
            return response()->json([
                'error' => 'Ha alcanzado el limite de imagenes permitidas.'
            ], 422);
        }

        $image_url = $images->store('public/images');
       
        $rutaImagenSinPublic = str_replace('public/images/', 'storage/images/', $image_url);
        $user->images()->create(['url' => $rutaImagenSinPublic]);
        $user->refresh();

        return response()->json($user->images,201);
    }

    public function destroy(Image $image){
        if($image->delete()){
            return response()->json([],204);
        }
        return response()->json([],404);
        
    }
}
