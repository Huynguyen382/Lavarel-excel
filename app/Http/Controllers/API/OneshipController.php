<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OneshipRequest;
use App\Models\Oneship;

class OneshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Oneship::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OneshipRequest $request)
    {
        $oneship = Oneship::create($request->all());
        return response()->json($oneship, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $oneship = Oneship::find($id);
        if(!$oneship) {
            return response()->json(['error' => 'Oneship not found'], 404);
        }
        return response()->json($oneship, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OneshipRequest $request, string $id)
    {
        $oneship = Oneship::find($id);
        if(!$oneship) {
            return response()->json(['error' => 'Oneship not found'], 404);
        }
        $oneship->update($request->all());
        return response()->json($oneship, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $oneship = Oneship::find($id);
        if(!$oneship) {
            return response()->json(['error' => 'Oneship not found'], 404);
        }
        $oneship->delete();
        return response()->json(['message'=>'Delete Successfully'], 204);
    }
}
