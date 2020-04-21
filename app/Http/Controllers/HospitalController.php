<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HospitalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index() {
        $hospital = DB::table('hospital')->get();
        return response()->json([
            'data' => $hospital
        ]);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'string|required',
            'city' => 'string|required',
            'province' => 'string|required',
            'x' => 'numeric|required',
            'y' => 'numeric|required'
        ]);

        $province = $request->province;
        $city = $request->city;
        $name = $request->name;
        $x = $request->x;
        $y = $request->y;

        $p = DB::table('province')->where('name', $province)->first();
        if ($p == null) {
            $id_province = DB::table('province')->insertGetId([
                'name' => $province
            ]);
        } else {
            $id_province = $p->id;
        }

        $c = DB::table('city')->where('name', $city)->first();

        if ($c == null) {
            $id_city = DB::table('city')->insertGetId([
                'name' => $city,
                'id_province' => $id_province
            ]);
        } else {
            $id_city = $c->id;
        }

        $hospital = DB::table('hospital')->insert([
            'name' => $name,
            'id_city' => $id_city,
            'x' => $x,
            'y' => $y
        ]);

        return response()->json([
            'message' => 'Succes Add Hospital',
            'data' => $hospital
        ]);
    }
}
