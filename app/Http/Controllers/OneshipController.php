<?php

namespace App\Http\Controllers;

use App\Models\Oneship;
use App\Http\Requests\SearchOneshipRequest;
use App\Models\vnpostModel;


class OneshipController extends Controller
{

    public function index(SearchOneshipRequest $request)
    {
        $search = $request->input('search', '');
        $limit = 1000;

        $query = Oneship::query();
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q -> where('e1_code', 'like',"%$search%")
                   -> orWhere('release_date', 'like',"%$search%")
                   -> orWhere('chargeable_volumn',(int) $search);
            });
        }
        $oneships = $query->orderBy('release_date', 'desc')->paginate($limit);
        $totalRows = Oneship::count();
        return view('oneship.index', compact('oneships','totalRows'));
    }
    public function vnpost(SearchOneshipRequest $request)
    {
        $search = $request->input('search', '');
        $limit = 1000;

        $query = vnpostModel::query();
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q -> where('e1_code', 'like',"%$search%")
                   -> orWhere('release_date', 'like',"%$search%")
                   -> orWhere('chargeable_volumn',(int) $search);
            });
        }
        $vnpost = $query->orderBy('release_date', 'desc')->paginate($limit);
        $totalRows = vnpostModel::count();
        return view('vnpost.index', compact('vnpost','totalRows', 'search'));
    }
}
