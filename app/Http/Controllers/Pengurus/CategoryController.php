<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Category;
use DataTables;
use Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" data-id="/pengurus/category/'.$row->id_kategori.'/edit" class="edit btn btn-warning btn-sm mr-1 editBtn">Edit</button>';
                    $btn .= '<button type="submit" data-id="/pengurus/category/'.$row->id_kategori.'" class="btn btn-danger btn-sm deleteBtn">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pengurus.manage_category.index');
    }

    public function create(){
        // pake modal dialog
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|max:255',
            'gambar' => 'required|image|max:2048|mimes:jpeg,jpg,png,gif',
            'pdf' => 'max:2048|mimes:pdf',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }else {
            $category = new Category;
            $category->nama_kategori = $request->nama_kategori;

            if($pdf = $request->file('pdf')){
                $new_name = date('Y-m-d-H:i:s') . '-' . rand() . '.' . $pdf->getClientOriginalExtension();
                Storage::putFileAs('public/category/pdf', $pdf, $new_name); 
                $category->pdf = $new_name;
            }

            if($image = $request->file('gambar')){
                $new_name = date('Y-m-d-H:i:s') . '-' . rand() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/category/photos', $image, $new_name); 
                $category->gambar = $new_name;
            }

            $category->save();
            return response()->json(['success' => true]);
        }
    }

    public function show($id){
        // tidak ditampilkan
    }

    public function edit($id){
        $data = Category::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|max:255',
            'gambar' => 'image|max:2048|mimes:jpeg,jpg,png,gif'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }else {
            $category = Category::find($id);
            $category->nama_kategori = $request->nama_kategori;

            if($image = $request->file('gambar')){
                $new_name = date('Y-m-d-H:i:s') . '-' . rand() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/category/photos', $image, $new_name); 
                if($category->gambar != NULL){
                    Storage::disk('public')->delete('category/photos/'.$category->gambar);
                }
                $category->gambar = $new_name;
            }

            if($pdf = $request->file('pdf')){
                $new_name = date('Y-m-d-H:i:s') . '-' . rand() . '.' . $pdf->getClientOriginalExtension();
                Storage::putFileAs('public/category/pdf', $pdf, $new_name); 
                if($category->pdf != NULL){
                    Storage::disk('public')->delete('category/pdf/'.$category->pdf);
                }
                $category->pdf = $new_name;
            }
            
            $category->save();
            return response()->json(['success' => true]);
        }
    }

    public function destroy($id){
        $data = Category::findOrFail($id);
        
        if(Storage::disk('public')->exists('category/photos/'.$data->gambar) == 1){
            Storage::disk('public')->delete('category/photos/'.$data->gambar);
        }

        if(Storage::disk('public')->exists('category/pdf/'.$data->pdf) == 1){
            Storage::disk('public')->delete('category/pdf/'.$data->pdf);
        }

        if (Category::destroy($id)) {
            $data = 'Success';
        }else {
            $data = 'Failed';
        }
        return response()->json($data);
    }
}
