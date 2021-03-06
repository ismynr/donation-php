<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Donatur;
use App\User;
use DataTables;
use Validator;

class ManageDonaturController extends Controller
{
    public function __construct(){
        $this->Donatur = new Donatur;
        $this->title = 'Donatur';
        $this->pasth = 'donatur';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Donatur::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" onclick="location.href =\''.route('admin.donatur.show', $row->id_donatur).'\'" class="detail btn btn-info btn-sm mr-1 detailBtn">Detail</button>';
                    $btn .= '<button type="button" data-id="/admin/donatur/'.$row->id_donatur.'/edit" class="edit btn btn-warning btn-sm mr-1 editBtn">Edit</button>';
                    $btn .= '<button type="submit" data-id="/admin/donatur/'.$row->id_donatur.'" class="btn btn-danger btn-sm deleteBtn">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.manage_donaturs.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'no_hp' => 'required|min:9',
            'alamat' => 'required',
            'umur' => 'required',
            'email' => 'required|unique:users|email'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }else {
            $user = new User;
            $user->name = $request->nama_depan . ' ' . $request->nama_belakang;
            $user->email = $request->email;
            $user->role = "donatur";
            $user->password = Hash::make($request->email);
            $user->save();

            $donatur = new Donatur;
            $donatur->id_user = $user->id;
            $donatur->nama_depan = $request->nama_depan;
            $donatur->nama_belakang = $request->nama_belakang;
            $donatur->no_hp = $request->no_hp;
            $donatur->alamat = $request->alamat;
            $donatur->umur = $request->umur;
            $donatur->email = $request->email;
            $donatur->save();

            return response()->json(['success' => true]);
        }
    }

    public function show($id)
    {
        $donaturs = Donatur::with('donasi')->find($id);
        return view('admin.manage_donaturs.detail', compact('donaturs'));
    }

    public function edit($id)
    {
        $data = Donatur::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $get = Donatur::where('id_donatur', $id)->first();
        $validator = Validator::make($request->all(), [
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'no_hp' => 'required|min:9',
            'alamat' => 'required',
            'umur' => 'required',
            'email' => 'email|required|max:255|unique:users,email,'. $get->id_user
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }else {
            $donatur = Donatur::find($id);
            $donatur->nama_depan = $request->nama_depan;
            $donatur->nama_belakang = $request->nama_belakang;
            $donatur->no_hp = $request->no_hp;
            $donatur->alamat = $request->alamat;
            $donatur->umur = $request->umur;
            $donatur->email = $request->email;
            $donatur->save();

            // UPDATE JUGA USER ACCOUNT PADA TABEL USER
            $users = User::find($donatur->id_user);
            $users->email = $request->email;
            if($request->password == "Reset Password"){
                $users->password = Hash::make($request->email);
            }
            $users->save();
            
            return response()->json(['success' => true]);
        }
    }

    public function destroy($id)
    {
        if (Donatur::destroy($id)) {
            $data = 'Success';
        }else {
            $data = 'Failed';
        }
        return response()->json($data);
    }
}
