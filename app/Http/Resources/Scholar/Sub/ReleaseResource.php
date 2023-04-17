<?php

namespace App\Http\Resources\Scholar\Sub;

use App\Models\BenefitList;
use App\Models\Scholar;
use App\Http\Resources\NameResource;
use App\Http\Resources\Benefit\ListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReleaseResource extends JsonResource
{
    public function toArray($request)
    {   
        $scholars = BenefitList::select('scholar_id')->where('release_id',$this->id)->groupBy('scholar_id')->pluck('scholar_id');
        $lists = Scholar::with('profile')->with('benefits.benefit')
        ->withWhereHas('benefits', function ($query) use ($scholars) {
            $query->where('release_id',$this->id);
        })
        ->whereIn('id',$scholars)
        ->get();

        return [
            'id' => $this->id,
            'number' => $this->number,
            'month' => date('F Y', strtotime($this->created_at)),
            'dv_no' => ($this->dv_no == NULL) ? 'Not Available' : $this->dv_no,
            'added_by' => new NameResource($this->user),
            'total' => $this->total,
            'benefits' => $scholars,
            'lists' => ListResource::collection($lists),
            'created_at' => $this->created_at
        ];
    }
}
