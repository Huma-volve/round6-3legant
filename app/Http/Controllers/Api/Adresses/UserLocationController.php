<?php

namespace App\Http\Controllers\Api\Adresses;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserLocationRequest;
use App\Http\Requests\StoreUserLocationRequest;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    // store address
    public function store (StoreUserLocationRequest $request)
    {
        $address = $request->user()->locations()->create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Address added successfully',
            'data' => $address,
        ], 201);
    }

    // update address
    public function update (UpdateUserLocationRequest $request, $id)
    {
        $address = $request->user()->locations()->find($id);

        if (!$address){
            return response()->json([
                'status' => false,
                'message' => 'Address not found or you do not have permission',
            ], 404);
        }

        $address->update($request->validated());
        // dd($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully',
            'data' => $address,
        ]);
    }

    // delete address
    public function destroy (Request $request, $id)
    {
        $address = $request->user()->locations()->find($id);

        if (!$address){
            return response()->json([
                'status' => false,
                'message' => 'Address not found or you do not have permission',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully',
        ]);
    }
}
