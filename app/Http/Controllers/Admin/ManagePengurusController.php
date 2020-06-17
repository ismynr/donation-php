<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Pengurus;
use App\User;
use DataTables;
use Validator;

class ManagePengurusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengurus::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" onclick="location.href =\' '.route('admin.pengurus.show', $row->id_pengurus).' \'" class="detail btn btn-info btn-sm mr-1 detailBtn">Detail</button>';
                    $btn .= '<button type="button" data-id="/admin/pengurus/'.$row->id_pengurus.'/edit" class="edit btn btn-warning btn-sm mr-1 editBtn">Edit</button>';
                    $btn .= '<button type="submit" data-id="/admin/pengurus/'.$row->id_pengurus.'" class="btn btn-danger btn-sm deleteBtn">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.manage_pengurus.pengurus');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validate for form create
        $validator = Validator::make($request->all(), [
            'nip'       => 'required|numeric',
            'nama'      => 'required|min:2',
            'jabatan'   => 'required',
            'email'     => 'required|email|unique:users',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }else {
            // Make User Login
            $users = new User;
            $users->name = $request->nama;
            $users->email = $request->email;
            $users->role = 'pengurus';
            $users->password = Hash::make($request->email);
            $users->save();

            // Make Data Pengurus
            $pengurus = new Pengurus;
            $pengurus->id_user = $users->id;
            $pengurus->nip = $request->nip;
            $pengurus->nama = $request->nama;
            $pengurus->jabatan = $request->jabatan;
            $pengurus->save();

            return response()->json(['success' => true]);
        }
    }

    public function show($id)
    {
        $data = Pengurus::findOrFail($id); // Mengambil satu Pengurus
        return view('admin.manage_pengurus.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = Pengurus::findOrFail($id);
        $user = User::findOrFail($data->id_user);
        return response()->json(['data' => $data, 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $get = Pengurus::where('id_pengurus', $id)->first();

        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'nama' => 'required',
            'jabatan' => 'required',
            'email' => 'email|required|max:255|unique:users,email,'. $get->id_user
        ]);
        
        if($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }else {
            $pengurus = Pengurus::find($id);
            $pengurus->nip = $request->nip;
            $pengurus->nama = $request->nama;
            $pengurus->jabatan = $request->jabatan;
            $pengurus->save();
            
            // UPDATE JUGA USER ACCOUNT PADA TABEL USER
            $users = User::find($pengurus->id_user);
            $users->name = $request->nama;
            $users->email = $request->email;
            if($request->password == "Reset Password"){
                $donatur->password = Hash::make($request->email);
            }
            $users->save();
            return response()->json(['success' => true]);
        }
    }

    public function destroy($id)
    {
        $pengurus = Pengurus::find($id);
        if (Pengurus::destroy($id)) {
            $data = 'Success';
        }else {
            $data = 'Failed';
        }
        return response()->json($data);
    }
}
