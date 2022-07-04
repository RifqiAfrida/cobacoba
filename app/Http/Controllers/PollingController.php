<?php

namespace App\Http\Controllers;

// use Dotenv\Validator;
use App\Models\Polling;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PollingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $polling = Polling::with('choose')->get();
        $status = 200;
        if (count($polling) > 0) {
            $data = [
                "status" => true,
                "pollingname" => $polling,
            ];
        } else {
            $status = 401;
            $data = [
                "status" => false,
                "message" => "Nilai Polling tidak ada",
            ];
        }
        return response()->json($data, $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi 
        $validator = Validator::make($request->all(),[
            "pollingname" => "required|string",
            "start_date"  => "required",
            "end_date"    => "required",
            "status"      => "required|boolean"
        ]);

        if ($validator->fails()) {
            return \response()->json([
                "status" => false,
                "validation" => $validator->errors()->toArray()], 422);
        }

        $polling = Polling::insertGetId([
            "pollingname" => $request->pollingname,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "status"      => $request->status
        ]);

        if ($polling) {
            return \response()->json([
                "status"      => true,
                "pollingname" => Polling::find($polling)
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
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $polling = Polling::where("id", $id)->with("choose")->first();
        $status  = 200;

        if ($polling) {
            $data = [
                "status" => true, 
                "polling" => $polling,
            ];
        } else {
            $status = 422;
            $data = [
                "status" => false,
                "message" => "Data tidak ditemukan",
            ];
        }
        return response()->json($data, $status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validasi
        $validator = Validator::make($request->all(), [
            "pollingname" => "required|string",
            "start_date"  => "required",
            "end_date"    => "required",
            "status"      => "required|boolean"
        ]);
        if ($validator->fails()) {
            return \response()->json([
                "status" => false,
                "validation" => $validator->errors()->toArray()
            ], 422);
        }

        $polling = Polling::find($id)->update([
            "pollingname" => $request->pollingname,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "status"      => $request->status
        ]);

        if ($polling) {
            return \response()->json([
                "status" => true,
                "pollingname" => Polling::find($id),
                "message" => "Berhasil untuk update data"
            ], 200);

        } else {
            return \response()->json([
                "status" => false,
                "message" => "Gagal update data"
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Polling  $polling
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $polling = Polling::find($id)->delete();

        if ($polling) {
            return \response()->json([
                "status" => true,
                "pollingname" => $polling
            ], 200);
        } else {
            return \response()->json([
                "status" => false,
                "message" => "Gagal menghapus data"
            ], 422);
        }
    }
}
