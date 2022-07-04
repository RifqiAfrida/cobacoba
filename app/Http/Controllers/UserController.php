<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        //Validasi
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return \response()->json([
                "status" => false,
                "validation" => $validator->errors()->toArray()
            ], 422);
        }

        $data = User::where('name', $request->name)->first();

        if ($data != null) {
            if (Hash::check($request->password, $data->password)) {
                try {
                    if (!$token = JWTAuth::fromUser($data)) {
                        return response()->json(['error' => 'invalid_credentials'], 422);
                    } 
                } catch (JWTException $e) {
                    return response()->json(['error' => 'could_not_create_token'], 500);
                }
                $data = [
                    "status" => true,
                    "message" =>"Login Sukses",
                    "is_admin" => $data->is_admin == 1,
                    "user" => $data,
                    "token" => $token
                ];
            } else {
                $data = [
                    "status" => false,
                    "message" => "Password Salah"
                ];
            } 
        } else {
            $data = [
                "status" => false,
                "message" => "Your username/password salah"
            ];
        }

        return response()->json($data, 200);

    }



    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return \response()->json(['user_not_found'], 422);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return \response()->json(['token_expired'], $e->getCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return \response()->json(['token_invalid'], $e->getCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return \response()->json(['token_absent'], $e->getCode());
        }

        return \response()->json(compact('user'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('choose')->get();
        $status = 200;
        if (count($user) > 0) {
            $data = [
                "status" => true,
                "name" => $user,
            ];
        } else {
            $status = 401;
            $data = [
                "status" => false,
                "message" => "User tidak ada",
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
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:Users,name",
            "password" => "required",
            "is_admin" => "required",
        ]);

        if ($validator->fails()) {
            return \response()->json([
                "status" => false,
                "validation" => $validator->errors()->toArray()
            ], 422);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'password' => Hash::make($request->get('password')),
            'is_admin' => $request->get('is_admin')
        ]);

        return \response()->json([
            "status" => true,
            "user" => $user
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where(["id" => $id])->first();

        if ($user) {
            $data = [
                "status" => true,
                "user"   => $user
            ];
        } else {
            $data = [
                "status" => false,
                "message" => "User tidak ditemukan"
            ];
        }

        return \response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
        ]);

        if ($validator->fails()) {
            return \response()->json([
                "status" => false,
                "validation" => $validator->errors()->toArray()
            ], 422);
        }

        //Check Name
        $check = User::where("name", $request->name)->get();
        if ($check && $check->id != $id) {
            return \response()->json([
                "status" => false, 
                "message" => "Name has been taken"
            ], 400);
        }

        User::where(["id" => $id])->update([
            'name' => $request->get('name'),
        ]);

        return \response()->json([
            "status" => true,
            "user" => User::where([
                "id" => $id
            ])->get()
            ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $old_user = User::where(["id" => $id])->get();
        $user = User::where(["id" => $id])->delete();
        return ($user) 
            ? \response()->json([
                "status" => true,
                "user" => $old_user,
                "message" => "Hapus data berhasil"
            ], 201)
            : \response()->json([
                "status" => false,
                "message" => "Gagal hapus data"
            ], 201);
    }
}
