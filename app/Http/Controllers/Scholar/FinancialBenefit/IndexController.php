<?php

namespace App\Http\Controllers\Scholar\FinancialBenefit;

use App\Models\Scholar;
use App\Models\BenefitList;
use App\Models\BenefitRelease;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReleaseRequest;
use App\Http\Resources\Benefit\ListResource;
use App\Http\Resources\NameResource;
use App\Http\Resources\Scholar\EvaluationResource;
use App\Http\Resources\Scholar\Sub\ReleaseResource;

class IndexController extends Controller
{
    public function index(Request $request){
        $keyword = $request->keyword;
        if($request->search){
            $month = date_parse($request->month)['month'];
            $year = $request->year;
            $data = BenefitRelease::orderBy('created_at','DESC')
            ->when($month, function ($query, $month) {
                $query->whereMonth('created_at',$month);
            })
            ->when($year, function ($query, $year) {
                $query->whereYear('created_at',$year);
            })
            ->paginate($request->count)
            ->withQueryString();
            return ReleaseResource::collection($data);
        }else{
            return inertia('Modules/FinancialBenefits/Index');
        }
    }

    public function store(ReleaseRequest $request){
        $data = \DB::transaction(function () use ($request){
            $attachment = [];
            $data = BenefitRelease::create(
                array_merge($request->all(),[
                    'attachment' => json_encode($attachment),
                    'added_by' => \Auth::user()->id
                ])
            );
            foreach($request->lists as $list){
                foreach($list['benefits'] as $benefit){
                    $benefit = BenefitList::where('id',$benefit['id'])->first();
                    $benefit->status_id = 56;
                    $benefit->release_id = $data->id;
                    $benefit->save();
                }
            }
            return $data;
        });

        return back()->with([
            'message' => 'Released was successfull. Thanks',
            'data' =>  $data,
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function create(Request $request){
        switch($request->type){
            case 'lists':
                return $this->lists();
            break;
            case 'benefits':
                return $this->benefits($request->info);
            break;
            case 'view':
                return $this->view($request->id);
            break;
        }
    }

    public function lists(){
        $date = date("Y").'-'.date("m").'-1';
        $pending = BenefitList::where('status_id',55)->where('month','<=',$date)->groupBy('scholar_id')->pluck('scholar_id');
        $scholars = Scholar::with('profile.user')->whereIn('id',$pending)->get();
        $data = [
            'pending' => $pending,
            'scholars' => NameResource::collection($scholars),
            'month' => date('F', mktime(0, 0, 0, date("m"), 10)),
            'count' => BenefitRelease::whereYear('created_at', '=', date("Y"))->whereMonth('created_at', '=', date("m"))->count()

        ];
        return $data;
    }

    public function benefits($info){
        $scholars = (!empty(json_decode($info))) ? json_decode($info) : NULL;
        $month = date("Y").'-'.date("m").'-1';
        $data = Scholar::with('profile.user')->with('benefits.benefit')->with('program')
        ->withWhereHas('benefits', function ($query) use ($month) {
            $query->where('status_id',55)->where('month','<=',$month);
        })
        ->whereIn('id',$scholars)
        ->get();

        return ListResource::collection($data);
    }
}
