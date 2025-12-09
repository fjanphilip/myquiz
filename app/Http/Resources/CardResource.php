<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'study_set_id' => $this->study_set_id,
            'japanese_word' => $this->japanese_word,
            'japanese_reading' => $this->japanese_reading,
            'meaning' => $this->meaning,
            'example_sentence' => $this->example_sentence,
            'pitch_accent' => $this->pitch_accent,
            'is_mastered' => $this->is_mastered,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
