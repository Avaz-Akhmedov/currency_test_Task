<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'numberCode' => $this->number_code,
            'code' => $this->code,
            'value' => $this->value,
            'createdAt' => $this->created_at->format('Y-m-d H:m:i'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:m:i')
        ];
    }
}
