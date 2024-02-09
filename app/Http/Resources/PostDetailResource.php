<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'news_content' => $this->news_content,
            'author' => $this->author,
            'created_at' => date_format($this->created_at, 'd-m-Y'),

            // eager loading
            // Eager loading adalah solusi untuk mencegah pemanggilan query yang tidak kita sadari karena memanggil relasi model
            'writer' => $this->whenLoaded('writer'),

            // lazy loading
            // 'writer' => $this->writer
            // walaupun di controller tidak memaggil relasi model / with(namaModel), tapi ketika kita melakukan request tetap akan memanggil relasi, ini kurang efektif
            // jadi lebih baik kita menggunakan eager load,
        ];
    }
}
