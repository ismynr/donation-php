<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Donatur;
use App\User;
use DataTables;
use Validator;

class DonaturController extends Controller
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
                    $btn = '<button type="button" onclick="location.href =\''.route('pengurus.donatur.show', $row->id_donatur).'\'" class="detail btn btn-info btn-sm mr-1 detailBtn">Detail</button>';
                    $btn .= '<button type="button" data-id="/pengurus/donatur/'.$row->id_donatur.'/edit" class="edit btn btn-warning btn-sm mr-1 editBtn">Edit</button>';
                    $btn .= '<button type="submit" data-id="/pengurus/donatur/'.$row->id_donatur.'" class="btn btn-danger btn-sm deleteBtn">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pengurus.manage_donaturs.index');
    }

    public function create()
    {
        // pake modal dialog
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'no_hp' => 'required|min:9',
            'alamat' => 'required',
            'umur' => 'required',
            'email' => 'required',
            'pdf' => 'max:2048|mimes:pdf',
            'gambar' => 'image|max:2048|mimes:jpeg,jpg,png,gif',
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
            
            if($pdf = $request->file('pdf')){
                $new_name = Storage::putFile('public/donatur/pdf', $pdf); 
                $donatur->pdf = basename($new_name);
            }

            if($image = $request->file('gambar')){
                $new_name = Storage::putFile('public/donatur/photos', $image); 
                $donatur->gambar = basename($new_name);
            }
            $donatur->save();

            return response()->json(['success' => true]);
        }
    }

    public function show($id){
        $donaturs = Donatur::with('donasi')->find($id);
        return view('pengurus.manage_donaturs.detail', compact('donaturs'));
    }

    public function edit($id){
        $data = Donatur::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'no_hp' => 'required|min:9',
            'alamat' => 'required',
            'umur' => 'required',
            'pdf' => 'max:2048|mimes:pdf',
            'gambar' => 'image|max:2048|mimes:jpeg,jpg,png,gif'
            // 'email' => 'required'
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
            if($pdf = $request->file('pdf')){
                // KALO UPLOAD PDF LAGI
                $new_name = Storage::putFile('public/donatur/pdf', $pdf);

                // FILE PDF SEBELUMNYA DI HAPUS
                if($donatur->pdf != NULL){
                    Storage::delete('public/donatur/pdf/'.$donatur->pdf);
                }

                $donatur->pdf = basename($new_name);
            }
            if($image = $request->file('gambar')){
                $new_name = Storage::putFile('public/donatur/photos', $image);

                if($donatur->gambar != NULL){
                    Storage::delete('public/donatur/photos/'.$donatur->gambar);
                }

                $donatur->gambar = basename($new_name);
            }
            // $donatur->email = $request->email;
            // if($request->password == "Reset Password"){
            //     $donatur->password = Hash::make($request->email);
            // }
            $donatur->save();
            return response()->json(['success' => true]);
        }
    }

    public function destroy($id)
    {
        $data = Donatur::findOrFail($id);
        if(Storage::exists('public/donatur/pdf/'.$data->pdf) == 1){
            Storage::delete('public/donatur/pdf/'.$data->pdf);
        }

        if(Storage::exists('public/donatur/photos/'.$data->gambar) == 1){
            Storage::delete('public/donatur/photos/'.$data->gambar);
        }

        if (Donatur::destroy($id)) {
            $data = 'Success';
        }else {
            $data = 'Failed';
        }
        return response()->json($data);
    }
}
