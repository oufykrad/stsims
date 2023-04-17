<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Resources\LogsResource;
use App\Http\Resources\StaffResource;
use App\Http\Requests\StaffRequest;

class StaffController extends Controller
{
    public function index(Request $request){
        if($request->search){
            $data = StaffResource::collection(
                User::query()
                ->with('profile')
                ->when($request->keyword, function ($query, $keyword) {
                    $query->whereHas('profile',function ($query) use ($keyword) {
                        $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', "%{$keyword}%")
                        ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', "%{$keyword}%");
                    })->orWhere(function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', "%{$keyword}%")->whereNotIn('role',['Scholar','Administrator']);
                    });
                })
                ->whereNotIn('role',['Scholar','Administrator'])
                ->paginate(10)
                ->withQueryString()
            );
            return $data;
        }else{
            return inertia('Modules/Staffs/Index');
        }
    }

    public function store(StaffRequest $request){
        $data = \DB::transaction(function () use ($request){
            $data = User::create(array_merge($request->all(), ['password' => bcrypt('123456789')]));
            $data->profile()->create($request->all());
            $id = $data->id;
            if($request->img != ''){
                $data = $request->img;
                $img = explode(',', $data);
                $ini =substr($img[0], 11);
                $type = explode(';', $ini);
                if($type[0] == 'png'){
                    $image = str_replace('data:image/png;base64,', '', $data);
                }else{
                    $image = str_replace('data:image/jpeg;base64,', '', $data);
                }
                $image = str_replace(' ', '+', $image);
                $imageName = $request->username.'.'.$type[0];
                
                if(\File::put(public_path('images/avatars'). '/' . $imageName, base64_decode($image))){
                    $data = User::findOrFail($id);
                    $data->avatar = $imageName;
                    $data->save();
                }
            }

            return $data;
        });

        return back()->with([
            'message' => 'Staff created successfully. Thanks',
            'data' => new StaffResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function show(Request $request){
        if($request->staff == 'logs'){
            $data = Log::lists();
            return LogsResource::collection($data);
        }
        // $data = new StaffResource(User::findOrFail($request->staff));
        // return inertia('Modules/Staffs/Profile',['user' => $data]);
    }

    public function update(Request $request)
    {   
        $data = \DB::transaction(function () use ($request){
            $user = User::findOrFail($request->id);
            if($request->type === 'verify'){
                $user->verify();
            }else{
                $user->update($request->except('img','editable'));
            }
        });
        return true;

    }
}
