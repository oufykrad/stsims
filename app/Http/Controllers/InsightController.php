<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Scholar;
use App\Models\Qualifier;
use App\Models\ListAgency;
use App\Models\ListProgram;
use App\Models\ListCourse;
use App\Models\ProfileAddress;
use App\Models\SchoolCampus;
use App\Models\ScholarEducation;
use App\Models\LocationBarangay;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index(Request $request){
        if($request->search == 'lists'){
            return $this->home();
        }else if($request->search == 'years'){
            return $this->years($request);
        }else{
            return inertia('Modules/Insights/Index');
        }
    }

    public function home(){
        $agency_id = config('app.agency');
        $agency = ListAgency::with('region')->where('id',$agency_id)->first();
        $region_code = $agency->region_code;

        $array = [
            'first' => $this->first(),
            'courses' => $this->courses(),
            'schools' => $this->schools(),
            'gender' => Profile::select(\DB::raw('count(*) as total'))->whereIn('gender',[1,2])->groupBy('gender')->get(),
            'total' => Scholar::count(),
            'ongoing' => Scholar::whereHas('status',function ($query) {
                $query->where('type','ongoing');
            })->count(),
            'locations' => $this->location()
        ];

        return $array;
    }

    public function first($code = null){
        return [
            $this->scholars($code),
            $this->qualifiers($code),
            $this->graduates($code)
        ];
    }

    public function second($code = null){
        return [
            $this->scholars($code),
            $this->ongoing($code),
            $this->graduates($code)
        ];
    }

    public function scholars($code){
        $array = [];
        $data = Scholar::select('awarded_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('profile',function ($query) use ($code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('province_code',$code);
                });
            });
        })
        ->orderBy('awarded_year')->groupBy('awarded_year')->get();
        $len = count($data);
        
        $arr = [
            'name' => 'Scholars',
            'data' => $data
        ];
        array_push($array,$arr);

        return $arr = [
            'name' => 'Scholars',
            'icon' => 'bxs-user-circle',
            'color' => 'danger',
            'series' => $array,
            'number' => ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Scholar::when($code, function ($query, $code) {
                $query ->whereHas('profile',function ($query) use ($code) {
                    $query ->whereHas('address',function ($query) use ($code) {
                        $query->where('province_code',$code);
                    });
                });
            })->count(),
        ];
    }

    public function ongoing($code){
        $array = [];
        $data = Scholar::select('awarded_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('profile',function ($query) use ($code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('province_code',$code);
                });
            });
        })
        ->whereHas('status',function ($query) {
            $query->where('type','ongoing');
        })
        ->orderBy('awarded_year')->groupBy('awarded_year')->get();
        $len = count($data);
        
        $arr = [
            'name' => 'Ongoing',
            'data' => $data
        ];
        array_push($array,$arr);

        return $arr = [
            'name' => 'Ongoing',
            'icon' => 'bxs-check-circle',
            'color' => 'danger',
            'series' => $array,
            'number' => ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Scholar::when($code, function ($query, $code) {
                    $query ->whereHas('profile',function ($query) use ($code) {
                        $query ->whereHas('address',function ($query) use ($code) {
                            $query->where('province_code',$code);
                        });
                    });
                })->whereHas('status',function ($query) {
                    $query->where('type','ongoing');
                })->count(),
        ];
    }

    public function qualifiers($code){
        $array = [];
        $data = Qualifier::select('year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('profile',function ($query) use ($code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('province_code',$code);
                });
            });
        })
        ->orderBy('year')->groupBy('year')->get();
        $len = count($data);

        $arr = [
            'name' => 'Qualifiers',
            'data' => $data
        ];
        array_push($array,$arr);
        
        return $arr = [
            'name' => 'Qualifiers',
            'icon' => 'bx-notepad',
            'color' => 'primary',
            'series' => $array,
            'number' =>  ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Qualifier::when($code, function ($query, $code) {
                $query ->whereHas('profile',function ($query) use ($code) {
                    $query ->whereHas('address',function ($query) use ($code) {
                        $query->where('province_code',$code);
                    });
                });
            })->count(),
        ];
    }

    public function graduates($code){
        $array = [];
        $data = ScholarEducation::select('graduated_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('scholar',function ($query) use ($code) {
                $query ->whereHas('profile',function ($query) use ($code) {
                    $query ->whereHas('address',function ($query) use ($code) {
                        $query->where('province_code',$code);
                    });
                });
            });
        })
        ->whereNotNull('graduated_year')
        ->orderBy('graduated_year')->groupBy('graduated_year')->get();
        $len = count($data);

        $arr = [
            'name' => 'Graduates',
            'data' => $data
        ];
        array_push($array,$arr);
        
        return $arr = [
            'name' => 'Graduates',
            'icon' => 'bxs-graduation',
            'color' => 'success',
            'series' => $array,
            'number' =>  ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => ScholarEducation::when($code, function ($query, $code) {
                $query ->whereHas('scholar',function ($query) use ($code) {
                    $query ->whereHas('profile',function ($query) use ($code) {
                        $query ->whereHas('address',function ($query) use ($code) {
                            $query->where('province_code',$code);
                        });
                    });
                });
            })->whereNotNull('graduated_year')->count(),
        ];
    }

    public function schools(){
        $data = SchoolCampus::with('school')->withCount('scholars')->orderBy('scholars_count', 'desc')->take(5)->get();
        return $data;
    }

    public function courses(){
        $data = ListCourse::withCount('scholars')->orderBy('scholars_count', 'desc')->take(5)->get();
        return $data;
    }

    public function years(Request $request){
        $provinces = ProfileAddress::groupBy('province_code')->pluck('province_code');
        $programs = ListProgram::all();
        $year = 1994; $current_year =  date('Y'); $years = [];
        $province = ($request->province) ? $request->province : null;
        $is_undergrad = ($request->is_undergrad != null) ? $request->is_undergrad : null;
        $pro = ($request->program) ? $request->program : null;


        $prog = []; 
        foreach($programs as $program){
            $data = []; $year = 1994;
            for($year; $year <= $current_year; $year++ ){
                $years[] = $year;
                $data[] = ListProgram::where('id',$program->id)->withCount([
                'scholar', 
                'scholar as scholar_count' => function ($query) use ($year,$province,$is_undergrad,$pro){
                    $query->where('awarded_year', $year)
                    ->whereHas('profile',function ($query) use ($province,$pro) {
                        $query->whereHas('address',function ($query) use ($province,$pro) {
                            ($province != null) ? $query->where('province_code', $province) : '';
                        });
                    });
                    ($is_undergrad != null) ? $query->where('is_undergrad', $is_undergrad) : '';
                    ($pro != null) ? $query->where('program_id', $pro) : '';
                }])->pluck('scholar_count')->first();
            }
            $arr[] = [
                'name' => $program->name,
                'data' => $data  
            ];
            
        }

        return $y =[
            'categories' => $years,
            'programs' => $programs,
            'provinces' => LocationProvince::whereIn('code',$provinces)->get(),
            'lists' => $arr
        ];
    }

    public function years2($code){
        $programs = ListProgram::all();
        $year = 1994; $current_year =  date('Y'); $years = [];
        $province = $code;
        // $is_undergrad = ($request->is_undergrad != null) ? $request->is_undergrad : null;
        // $pro = ($request->program) ? $request->program : null;


        $prog = []; 
        foreach($programs as $program){
            $data = []; $year = 1994;
            for($year; $year <= $current_year; $year++ ){
                $years[] = $year;
                $data[] = ListProgram::where('id',$program->id)->withCount([
                'scholar', 
                'scholar as scholar_count' => function ($query) use ($year,$province){
                    $query->where('awarded_year', $year)
                    ->whereHas('profile',function ($query) use ($province) {
                        $query->whereHas('address',function ($query) use ($province) {
                            ($province != null) ? $query->where('province_code', $province) : '';
                        });
                    });
                }])->pluck('scholar_count')->first();
            }
            $arr[] = [
                'name' => $program->name,
                'data' => $data  
            ];
            
        }

        return $y =[
            'categories' => $years,
            'programs' => $programs,
            'lists' => $arr
        ];
    }
    
    public function location(){
        // $provinces = ProfileAddress::select('province_code')->groupBy('province_code')->get();
        
        // $data = LocationProvince::withCount('scholars')->whereIn('code',$provinces)->orderBy('scholars_count','DESC')->get();
        // return $data;

        $provinces = ProfileAddress::groupBy('province_code')->pluck('province_code');
        $provinces = LocationProvince::withCount('scholars')->whereIn('code',$provinces)->orderBy('scholars_count','DESC')->get();
        $programs = ListProgram::all();

        $array = []; $sums = []; $total = [];
        
        foreach($provinces as $key=>$province){
            $code = $province->code;
            $count = [];
            foreach($programs as $key2=>$program){
                $data = Scholar::whereHas('profile',function ($query) use ($code) {
                    $query->whereHas('address',function ($query) use ($code) {
                        $query->where('province_code',$code);
                    });
                })
                ->where('program_id',$program->id)->count();
                array_push($count,$data);    
                $sums[$key2][$key] = $data;
            }

            $array[] = [
                'province' => $province->name,
                'code' => $province->code,
                'count' => $count,
                'total' => array_sum($count)
            ];
        }

        foreach($programs as $key2=>$program){
            $total[] = array_sum($sums[$key2]); 
        }

        $array[] = [
            'province' => 'Total',
            'count' => $total,
            'total' => array_sum($total)
        ];
        
        $all = [
            'provinces' => $array,
            'totals' => $total,
            'programs' => $programs
        ];

        return $all;
    }

    public function edit($code){
        $programs = ListProgram::all();
        $province = LocationProvince::withCount('scholars')->with('region')->where('code',$code)->first();
        // $count = ProfileAddress::with(['municipality' => function($query){
        //     $query->groupBy('district');
        // }])->count();
        // $c = $municipality->code;

        // if($municipality->is_chartered){
        //     $districts = ProfileAddress::select('district', \DB::raw('count(*) as total'))
        //     ->where('province_code',$code)->groupBy('district')->get();
        // }else{
        //     $districts = ProfileAddress::select('district', \DB::raw('count(*) as total'))
        //     ->where('province_code',$code)->groupBy('district')->get();
        // }
        $districts = ProfileAddress::select('district', \DB::raw('count(*) as total'))
        ->whereHas('municipality',function ($query) {
            $query->where('is_chartered',0);
        })
        ->where('province_code',$code)->groupBy('district')->get();
         $array2 = [];
        foreach($districts as $district){
            $dis = $district->district;
            $array = [];
            foreach($programs as $program){
                $data = Scholar::whereHas('profile',function ($query) use ($code,$dis) {
                    $query->whereHas('address',function ($query) use ($code,$dis) {
                        $query->where('province_code',$code)->where('district',$dis);
                    });
                })
                ->where('program_id',$program->id)->count();

                $array[] = [
                    'program' => $program->name,
                    'count' => $data
                ];
            }
            $array2[] = [
                'district' => $district->district,
                'counts' => $array,
                'total' => $district->total
            ];
        }

        return [
            'first' => $this->second($code),
            'province' => $province,
            'districts' => $array2,
            'programs' => $programs,
            'categories' => $this->years2($code)

            // 'count' => $count,
            // 'code' => $code
        ];
    }
}

// $array= [
//     'statuses' => ListDropdown::where('classification','Status')->withCount('status')->orderBy('status_count', 'desc')->get(),
//     'total' => Scholar::count(),
//     'incomplete' => Scholar::where('is_completed',0)->count(),
//     'undergrad' => Scholar::where('is_undergrad',1)->count(),
//     'jlss' => Scholar::where('is_undergrad',0)->count(),
//     'registered' => User::where('role','Scholar')->count()
// ];
// return $array;