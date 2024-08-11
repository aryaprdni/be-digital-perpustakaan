<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'description' => $this->description,
            'quantity' => $this->quantity,
            'pdf_file' => $this->pdf_file,
            'cover_image' => $this->cover_image,
            'created_by' => $this->created_by,
            'category' => new CategoryResource($this->whenLoaded('category'))
        ];
    }
}
