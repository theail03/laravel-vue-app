<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Return an array that represents the resource
        return [
            'id' => $this->id,
            'matrix_id' => $this->matrix_id,
            'row' => $this->row,
            'column' => $this->column,
            'path' => URL::to($this->path),
        ];
    }
}

