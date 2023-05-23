<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Scholar;
use App\Models\ListDropdown;
use App\Models\ListAgency;
use App\Models\EnrolledList;
use App\Models\BenefitList;
use App\Models\SchoolSemester;
use App\Models\SchoolCampus;
use App\Models\ScholarEnrollment;
use Illuminate\Http\Request;
use App\Http\Resources\Scholar\IndexResource;

class MonitoringController extends Controller
{
    public function index(Request $request){
       
        if($request->search == 'counts'){
            $array = [
                'grades' => $this->grades(),
                'benefits' => $this->benefits(),
                'enrolled' => $this->enrolled(),
                'semesters' => $this->semesters(),
                'schools' => $this->schools(),
                'scholars' => $this->scholars(),
                'statuses' => $this->statuses(),
            ];

            return $array;
        }else if($request->search){
            $info = (!empty(json_decode($request->info))) ? json_decode($request->info) : NULL;
            $filter = (!empty(json_decode($request->filter))) ? json_decode($request->filter) : NULL;
    
            $data = IndexResource::collection(
                Profile::
                with('address.region','address.province','address.municipality','address.barangay','user')
                ->with('scholar.program','scholar.benefits.semester.semester','scholar.benefits.benefit','scholar.enrollments.lists')
                ->with('scholar.education.school.school','scholar.education.course')
                ->with('scholar.education.school.semesters.semester')->with('scholar.education.school.term')
                ->when($info->keyword, function ($query, $keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                    ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%');
                })
                ->whereHas('address',function ($query) use ($filter) {
                    if(!empty($filter)){
                        (property_exists($filter, 'region')) ? $query->where('region_code',$filter->region) : '';
                        (property_exists($filter, 'province')) ? $query->where('province_code',$filter->province) : '';
                        (property_exists($filter, 'municipality')) ? $query->where('municipality_code',$filter->municipality) : '';
                        (property_exists($filter, 'barangay')) ? $query->where('barangay_code',$filter->barangay) : '';
                    }
                })
                ->whereHas('scholar',function ($query) use ($filter) {
                    $query->whereHas('education',function ($query) use ($filter) {
                        if(!empty($filter)){
                            (property_exists($filter, 'school')) ? $query->where('school_id',$filter->school) : '';
                            (property_exists($filter, 'course')) ? $query->where('course_id',$filter->course) : '';
                        }
                    });
                })
                ->whereHas('scholar',function ($query) use ($info) {
                    ($info->program == null) ? '' : $query->where('program_id',$info->program);
                    ($info->status == null) ? '' : $query->where('status_id',$info->status);
                    ($info->is_undergrad == 'all') ? '' : $query->where('is_undergrad',$info->is_undergrad);
                    ($info->year == null) ? '' : $query->where('awarded_year',$info->year);
                    })
                ->whereHas('scholar',function ($query){
                    $query->whereHas('status',function ($query){
                        $query->where('type','Ongoing');
                    });
                })
                ->orderBy('lastname',$info->sorty)
                ->paginate($info->counts)
                ->withQueryString()
            );
            return $data;
        }else{
            return inertia('Modules/Monitoring/Index');
        }
    }

    public function statuses(){
        $data = ListDropdown::where('type','ongoing')->withCount('status')->orderBy('status_count', 'desc')->get();
        return $data;
    }

    public function grades(){
        $data = ScholarEnrollment::whereHas('lists',function ($query){
            $query->where('grade',NULL);
        })
        ->whereHas('semester',function ($query){
            $query->where('is_active',0);
        })
        ->pluck('scholar_id');
        return $data;
    }

    public function benefits(){
        $date = date('Y-m-d');
        $data = BenefitList::where('status_id',55)->where('month','<=',$date)->groupBy('scholar_id')->pluck('scholar_id');
        return $data;
    }

    public function enrolled(){
        $data = EnrolledList::whereHas('semester',function ($query){
            $query->where('is_active',1);
        })->pluck('scholar_id');
        return $data;
    }

    public function semesters(){
        $data = SchoolSemester::where('is_active',1)->pluck('school_id');
        return $data;
    }

    public function schools(){
        $agency_id = config('app.agency');
        $region = ListAgency::select('region_code')->where('id',$agency_id)->first();
        $region = $region->region_code;

        $data = SchoolCampus::query()->whereHas('municipality',function ($query) use ($region){
            $query->whereHas('province',function ($query) use ($region){
                $query->whereHas('region',function ($query) use ($region){
                    $query->where('code',$region);
                });
            });
        })->count();
        return $data;
    }

    public function scholars(){
        $data = Scholar::whereHas('status',function ($query){
                $query->where('type','Ongoing');
            })->count();
        return $data;
    }
}
