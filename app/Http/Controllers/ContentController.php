<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\GeneralTrait;

use App\Models\Content;
use App\Models\Log;
use Hash;
use Auth;

class ContentController extends Controller
{

    use GeneralTrait;

    public function index(Request $request)
    {

        $page_title = 'Header Contents of KOBAR Connect';
        $page_description = 'Berikut adalah ragam konten untuk ditampilkan sebagai header di aplikasi KOBAR Content';
        $data = Content::orderBy("order", "asc")->paginate(12);

        return view('pages.content.list_content', compact(
            'data',
            'page_title',
            'page_description'
        ));
    }

    public function store(Request $request){

        $id = $request->get("id");
        $file_validation = 'required';
        if(isset($id)){
            $file_validation = '';
        }
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'file' => $file_validation.'|mimes:jpg,jpeg,png,mp4|max:20000',
        ]);


        try {
            $data = $request->all();
            $file = $request->file('file');

            if(isset($file)){
                $mime = $file->getMimeType();
                if(strstr($mime, "video/")){
                    $data["file_type"] = config('constant.FILE_TYPE')["VIDEO"];
                }else if(strstr($mime, "image/")){
                    $data["file_type"] = config('constant.FILE_TYPE')["IMAGE"];
                }
                $upload_result = $this->upload($file, null);
                if(!$upload_result["status"]){
                    throw new \Exception("Failed upload file");
                }
            }

            if(isset($id)){
                $content = Content::findOrFail($id);
                $content->message = $request->get("message");
                $content->order = $request->get("order");
                if(isset($upload_result)){
                    $content->path = $upload_result["data"];
                }

                $result = $content->save();
            }else{
                $data["created_by"] = Auth::user()->id;
                $data["path"] = $upload_result["data"];

                // Get bigest order
                $check = Content::orderBy("order","desc")->first();
                $new_order = 1;
                if($check){
                    $old_order = $check->order;
                    $new_order = $old_order + 1;
                }

                $data["order"] = $new_order;
                $result = Content::create($data);
            }

            if($result){
                if(isset($id)){
                    Log::create([
                        'id_user' => Auth::user()->id,
                        'name' => "update kontent $id"
                    ]);
                }else{
                    Log::create([
                        'id_user' => Auth::user()->id,
                        'name' => "create kontent ".$result->name
                    ]);
                }
                return redirect()->back()->with("success", "Berhasil tambah data");
            }
            return redirect()->back()->with("error", "Gagal tambah data");
        } catch (\Exception $th) {
            return $th->getMessage();
            return redirect()->back()->with("error", "Gagal tambah data");
        }
    }

    public function destroy($id){
        $content = Content::findOrFail($id);
        $result = $content->delete();

        if($result){
            Log::create([
                'id_user' => Auth::user()->id,
                'name' => "hapus kontent ".$id
            ]);
            return redirect()->back()->with("success", "Berhasil Hapus Data");
        }
        return redirect()->back()->with("error", "Gagal Hapus Data");
    }

    // ======================================
    // API
    // ======================================
    public function contents(Request $request){
        $result = Content::all();
        return response()->json($result);
    }
}
