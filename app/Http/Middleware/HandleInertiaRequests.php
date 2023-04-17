<?php

namespace App\Http\Middleware;

// use App\Models\College;
// use App\Models\Course;
use App\Models\ListProgram;
use App\Models\ListDropdown;
use App\Models\LocationRegion;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\UserResource;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => (\Auth::check()) ? new UserResource(\Auth::user()) : '',
            'role' => (\Auth::check()) ? \Auth::user()->role : '',
            'flash' => [
                'message' => session('message'),
                'datares' => session('data'),
                'type' => session('type')
            ],
            'regions' => LocationRegion::all(),
            'dropdowns' => ListDropdown::all(),
            'programs' => ListProgram::all(),
            // 'colleges' => College::all(),
            // 'courses' => Course::all(),
            // 'dropdowns' => Dropdown::all(),
        ]);
    }
}
