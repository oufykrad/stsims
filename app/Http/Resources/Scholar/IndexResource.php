<?php

namespace App\Http\Resources\Scholar;

use Hashids\Hashids;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray($request)
    {
        $info =  json_decode($this->information);
        $hashids = new Hashids('krad',10);
        $id = $hashids->encode($this->id);
        $this->scholar->education->courseInfo = ['name' => $info->course];
        $this->scholar->education->schoolInfo = ['name' => $info->school];
        $this->address->info = ['info' => $info->address];
        $this->scholar->education->scholar_id = $this->scholar->id;
        return [
            'id' => $this->scholar->id,
            'code' => $id,
            'lrn' => ($this->scholar->lrn == null) ? 'n/a' : $this->scholar->lrn,
            'spas_id' => ($this->scholar->spas_id == null) ? 'SPAS-'.str_pad(mt_rand(1,99999999),6,'0',STR_PAD_LEFT) : $this->scholar->spas_id,
            'awarded_year' => $this->scholar->awarded_year,
            'program' => $this->scholar->program,
            'status' => $this->scholar->status,
            'is_completed' => $this->scholar->is_completed,
            'is_undergrad' => $this->scholar->is_undergrad,
            'profile' => new ProfileResource($this), 
            'user' => ($this->user != null) ? new UserResource($this->user) : null,
            'address' => new AddressResource($this->address),
            'education' =>  new EducationResource($this->scholar->education),
            'created_at' => $this->scholar->created_at,
            'updated_at' => $this->scholar->updated_at,
            'info' => $info
        ];
    }
}
