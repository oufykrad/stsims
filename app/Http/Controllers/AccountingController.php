<?php

namespace App\Http\Controllers;

use App\Models\Allotment;
use App\Models\AllotmentList;
use App\Models\Disbursement;
use Illuminate\Http\Request;
use App\Http\Requests\AllotmentRequest;
use App\Http\Resources\Accounting\AllotmentResource;
use App\Http\Resources\Accounting\DisbursementResource;
use App\Http\Resources\Accounting\SuballotmentResource;
use App\Http\Resources\DefaultResource;

class AccountingController extends Controller
{
    public function index(Request $request){
        if($request->lists){
            if($request->type == 'allotments'){
                $data = AllotmentResource::collection(
                    Allotment::with('lists','user.profile')
                    ->when($request->keyword, function ($query, $keyword) {
                        $query->where('check_no', 'LIKE', '%'.$keyword.'%');
                    })
                    ->orderBy('created_at','DESC')
                    ->paginate($request->counts)
                    ->withQueryString()
                );
                return $data;
            }else{
                $data = DisbursementResource::collection(
                    Disbursement::with('expense','user.profile')
                    ->when($request->keyword, function ($query, $keyword) {
                        // $query->where('check_no', 'LIKE', '%'.$keyword.'%');
                    })
                    ->orderBy('created_at','DESC')
                    ->paginate($request->counts)
                    ->withQueryString()
                );
                return $data;
            }
        }else{
            return inertia('Modules/Accounting/Index');
        }
    }

    public function show($type){
        if($type == 'allotments'){
            return inertia('Modules/Accounting/Allotment/Index');
        }else{
            return inertia('Modules/Accounting/Disbursement/Index');
        }
    }

    public function store(AllotmentRequest $request){
        $type = $request->type;
        if($type == 'allotment'){
            $data = \DB::transaction(function () use ($request){
                $code = 'DOST-'.Allotment::count()+1;
                $data = Allotment::create(array_merge($request->all(),['code' => $code, 'added_by' => \Auth::user()->id]));
                return $data;
            });

            return back()->with([
                'message' => 'Allotment added successfully. Thanks',
                'data' =>  $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($type == 'suballotment'){
            $data = \DB::transaction(function () use ($request){
                $data = AllotmentList::create(array_merge($request->all(),['added_by' => \Auth::user()->id]));
                $data = AllotmentList::with('user.profile','expense')->where('id',$data->id)->first();
                return $data;
            });

            return back()->with([
                'message' => 'SubAllotment added successfully. Thanks',
                'data' =>  $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else{
            $data = \DB::transaction(function () use ($request){
                $data = Disbursement::create(array_merge($request->all(),['added_by' => \Auth::user()->id]));
                return $data;
            });

            return back()->with([
                'message' => 'Disbursement added successfully. Thanks',
                'data' =>  $data,
                'type' => 'bxs-check-circle'
            ]); 
        }
    }
}
