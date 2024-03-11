<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $imagenes = $this->images()->limit(3)->get();
     
        $imagenesArray = [];
        foreach ($imagenes as $indice => $imagen) {
            $imagenesArray["imagen" . ($indice + 1)] = $imagen->url;
        }
        $imagen1 = $imagenesArray['imagen1'] ?? '';
        $imagen2 = $imagenesArray['imagen2'] ?? '';
        $imagen3 = $imagenesArray['imagen3'] ?? '';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'url' => $this->url,
            'key' => 'password',
            'image1' =>  $imagen1,
            'image2' =>  $imagen2,
            'image3' => $imagen3,
        ];
    }
}
