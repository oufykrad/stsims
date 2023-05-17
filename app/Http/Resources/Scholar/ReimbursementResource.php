<?php

namespace App\Http\Resources\Scholar;

use Illuminate\Http\Resources\Json\JsonResource;

class ReimbursementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'attachment' => $this->attachment,
            'is_approved' => $this->is_approved,
            'is_reimbursed' => $this->is_reimbursed,
            'benefit' => $this->benefit,
            'semester' => $this->semester,
            'scholar' => new ProfileResource($this->scholar->profile),
            'checked_by' => $this->checked_by
        ];
    }
}
