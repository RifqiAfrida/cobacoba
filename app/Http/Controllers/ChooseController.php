<?php

namespace App\Http\Controllers;

use App\Models\Choose;
use Illuminate\Http\Request;

class ChooseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validasi

        $request->validate([
            "name" => "required",
            "pollings_id" => "required",
        ]);

        $choose = Choose::insertGetId([
            "name" => $request->get('name'),
            "pollings_id" => $request->get('pollings_id'),
        ]);
                if ($choose) {
            return \response()->json([
                "status"      => true,
                "name"        => Choose::find($choose)
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Gagal menambahkan suara pilihan"
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Choose  $choose
     * @return \Illuminate\Http\Response
     */
    public function show(Choose $choose)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Choose  $choose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Choose $choose)
    {
        //Validasi

        $validator = Validator::make($request->all(),[
            'name' => "required",
            'pollings_id' => "required"
        ]);

        if ($validator->fails()) {
            return \response()->json([
                "status" => false,
                "validation" => $validator->errors()->toArray()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Choose  $choose
     * @return \Illuminate\Http\Response
     */
    public function destroy(Choose $choose)
    {
        //
    }
}
