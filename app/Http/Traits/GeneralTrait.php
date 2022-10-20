<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Log;

trait GeneralTrait {

    public static function upload($file, $fileName = null)
    {
        try {
            if (empty($file)) {
                return null;
            }

            if (empty($fileName)) {
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $random =  substr(str_shuffle(str_repeat($pool, 5)), 0, 32);
                $name = time().$random.".".$file->getClientOriginalExtension();
            } else {
                $name = $fileName;
            }

            $folderPath = config("constant.IMAGE_PATH")["TOUR"];
            $fullpath = "/".$folderPath.$name;

            $path = $file->move($folderPath, $name);

            return [
                "status"    => true,
                "message"   => "success",
                "data"      => $fullpath,
            ];
        } catch (\Exception $th) {
            Log::channel("api_error")->info("Upload Image failed : ". $th->getMessage());
            return [
                "status"    => false,
                "message"   => "Failed upload image : ". $th->getMessage(),
                "data"      => null,
            ];
        }
    }

    public static function remove_file($list_file){
        try {
            $status = File::delete($list_file);
            return [
                "status"    => $status,
                "message"   => "Success hapus file",
            ];
        } catch (\Exception $th) {
            Log::channel("api_error")->info("Remove Image failed : ". $th->getMessage());
            return [
                "status"    => false,
                "message"   => "Failed remove image",
                "data"      => null,
            ];
        }
    }
}
