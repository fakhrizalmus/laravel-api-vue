<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request['limit'];
        $offset = 0;
        if (isset($request['offset']) && $request['offset'] != '') {
            $offset = $request['offset'];
        }
        if (auth()->user()->role_user->role == "Admin") {
            $data = Tugas::with('user');
        } else {
            $data = Tugas::with('user')->where('userid', auth()->user()->id);
        }
        if (isset($request['status']) && $request['status'] != "") {
            if ($request['status'] == 1) {
                $data = $data->where("status", "Selesai");
            } else {
                $data = $data->where("status", "Belum Selesai");
            }
        }
        if (isset($limit)) {
            $data = $data->skip($offset)->take($limit);
        }
        $data = $data->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()
            ], 400);
        }

        $tugas = Tugas::create([
            'name' => $request['name'],
            'status' => $request['status'],
            'userid' => auth()->user()->id
        ]);

        if ($tugas) {
            return response()->json([
                'status' => true,
                'data' => $tugas
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Input Fail'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tugas $tugas) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mytugas = DB::table('tugas')->where('userid', auth()->user()->id)->where('id', $id)->get();
        if (count($mytugas) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Tugas Not Found'
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()
            ], 400);
        }

        $data = Tugas::find($id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found'
            ], 404);
        }
        $data->name = $request['name'];
        $data->status = $request['status'];
        $data->userid = auth()->user()->id;
        $data->save();
        return response()->json([
            'status' => true,
            'data' => $data
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mytugas = DB::table('tugas')->where('userid', auth()->user()->id)->where('id', $id)->get();
        if (count($mytugas) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Tugas Not Found'
            ], 404);
        }
        $data = Tugas::find($id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found'
            ], 404);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Success Delete'
        ]);
    }
}
