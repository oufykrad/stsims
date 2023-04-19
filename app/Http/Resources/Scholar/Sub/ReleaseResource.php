<?php

namespace App\Http\Resources\Scholar\Sub;

use App\Models\BenefitList;
use App\Models\Scholar;
use App\Http\Resources\NameResource;
use App\Http\Resources\Benefit\ListResource;
use App\Http\Resources\Benefit\LbpResource;
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

        $note = '9999999999DEPT OF SCIENCE AND TECHN'.str_pad((str_replace('.', '', $this->total)), 15, '0', STR_PAD_LEFT).str_pad(('4533648200000'), 19, '0', STR_PAD_LEFT).str_pad((count($lists)), 5, '0', STR_PAD_LEFT).' '.str_pad((31), 5, '0', STR_PAD_LEFT); 

        return [
            'id' => $this->id,
            'number' => $this->number,
            'month' => date('F Y', strtotime($this->created_at)),
            'dv_no' => ($this->dv_no == NULL) ? 'Not Available' : $this->dv_no,
            'added_by' => new NameResource($this->user),
            'total' => $this->total,
            'benefits' => $scholars,
            'lists' => ListResource::collection($lists),
            'lbp' => LbpResource::collection($lists),
            'note' => $note,
            'created_at' => $this->created_at
        ];
    }
}
