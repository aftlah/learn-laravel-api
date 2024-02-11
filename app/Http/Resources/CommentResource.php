<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'comment' => $this->comment,
            "commentator" => $this->whenLoaded('commentator'),
            'created_at' => date_format($this->created_at, 'd-m-Y H:i:s'),
        ];
    }
}
