<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Hash;
use Auth;
use App\Models\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function create_user(Request $request)
    {

        $id = $request->get("id");
        if(isset($id)){
            $check = '';
            $check_email = 'unique:users,email,'.$id;
        }else{
            $check = 'required';
            $check_email = 'unique:users,email';
        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|'.$check_email,
            'password' => $check.'|max:255',
        ]);
        try {
            if(isset($id)){
                if($id != Auth::user()->id){
                    return redirect()->back()->with("error", "Tidak bisa update user ini");
                }
                $user = User::findOrFail($id);
                $user->name = $request->get("name");
                $user->email = $request->get("email");
                $password = $request->get("password");
                if(isset($password)){
                    $user->password = Hash::make($request->get("password"));
                }

                $user->save();

                Log::create([
                    'id_user' => Auth::user()->id,
                    'name' => "buat user ".$user->id
                ]);
            }else{
                $user = User::create([
                    "name"  => $request->get("name"),
                    "email" => $request->get("email"),
                    "password" => Hash::make($request->get("password")),
                    "id_status"    => config('constant.STATUS_ARRAY')["ACTIVE"]
                ]);

                $roles = Role::where("name", "Administrator")->get();
                $user->assignRole($roles);


                Log::create([
                    'id_user' => Auth::user()->id,
                    'name' => "update user ".$id
                ]);
            }

            return redirect()->back()->with("success", "Berhasil tambah data");
        } catch (\Exception $th) {
            return redirect()->back()->with("error", "Gagal tambah data");
        }
    }

    public function delete($id){
        try {
            $User = User::findOrFail($id);
            $User->id_status = config('constant.STATUS_ARRAY')["NOT_ACTIVE"];
            $User->save();

            Log::create([
                'id_user' => Auth::user()->id,
                'name' => "hapus user ".$id
            ]);
            return redirect()->back()->with("success", "Berhasil Hapus Data");
        } catch (\Exception $th) {
            return redirect()->back()->with("error", "Gagal Hapus Data");
        }
    }

    public function restore($id){
        try {
            $User = User::findOrFail($id);
            $User->id_status = config('constant.STATUS_ARRAY')["ACTIVE"];
            $User->save();

            Log::create([
                'id_user' => Auth::user()->id,
                'name' => "restore user ".$id
            ]);
            return redirect()->back()->with("success", "Berhasil restore Data");
        } catch (\Exception $th) {
            return redirect()->back()->with("error", "Gagal Hapus Data");
        }
    }


    // KTDatatables
    public function index()
    {
        $page_title = 'This is our administrators';
        $page_description = 'Berikut adalah administrator yang terdaftar dalam pengaturan konten untuk aplikasi ini';

        return view('pages.list_user', compact('page_title', 'page_description'));
    }

    public function get_users(Request $request){

        $pagination = $request->get("pagination");
        $limit = $pagination["perpage"] ?? 10;
        $current_page = $pagination["page"];
        $total_page = $pagination["page"];

        $total_data = User::count();
        $users = User::with('roles')
            ->skip(($current_page - 1) * $limit)
            ->take($limit)
            ->get([
            "id",
            "name",
            "email",
            "created_at",
            "id_status"
        ]);

        return response()->json([
            "meta" => [
                "sort" => "asc",
                "field"=> "Employee_Id",
                "page"=> $current_page,
                "pages"=> ceil($total_data / $limit),
                "perpage" => $limit,
                "total" => $total_data,
            ],
            "data"=> $users
        ]);
    }
    public function users(Request $request){
        $result = User::all();
        return response()->json($result);
}
}
