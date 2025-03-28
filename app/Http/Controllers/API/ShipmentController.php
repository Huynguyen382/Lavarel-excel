<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentIndexRequest;
use App\Http\Requests\ShipmentRequest;
use Illuminate\Http\Request;
use App\Models\Oneship;
use App\Models\vnpostModel;

class ShipmentController extends Controller
{
    public function index(ShipmentIndexRequest $request)
    {
        $type = $request->input('type');
        $limit = 1000;
        // $limit = $request-> validate('limit')?? 1000;
        // $page   = $request-> validate('page')??1;
        if ($type == 'oneships') {
            $shipments = Oneship::with('carrier')->paginate($limit);
        } else {
            $shipments = vnpostModel::with('carrier')->paginate($limit);
        };
        return response()->json($shipments, 200);
        // $limit = $request->validate()['limit'] ?? 1000;
        // $page = $request->validate()['page'] ?? 1;

        // $query = $type === 'oneships'? Oneship::query() : vnpostModel::query();
        // $total = $query->count();
        // $shipments = $query->with('carrier')
        // ->skip(($page - 1) * $limit)
        // ->take($limit)
        // ->get();
        // return response()->json([
        //     'data' => $shipments,
        //     'total' => $total,
        //     'limit' => $limit,
        // ], 200);
    }

    public function show(string $id)
    {

        $shipment = Oneship::with('carrier')->find($id);

        if (!$shipment) {
            $shipment = vnpostModel::with('carrier')->find($id);
        }

        return response()->json($shipment, 200);
    }
    
    public function store(ShipmentRequest $request)
    {
        $type = $request->validate()['type'];
        if ($type === 'oneships') {
            $shipment = Oneship::create($request->all());
        } else {
            $shipment = vnpostModel::create($request->all());
        }

        return response()->json($shipment, 201);
    }
    public function update(ShipmentRequest $request, $id)
    {
        $type = $request->validate()['type'];
        if ($type === 'oneships') {
            $shipment = Oneship::find($id);
        } else {
            $shipment = vnpostModel::find($id);
        }

        if (!$shipment) {
            return response()->json(['error' => 'Shipment not found'], 404);
        }

        $shipment->update($request->all());
        return response()->json($shipment, 200);
    }
}
