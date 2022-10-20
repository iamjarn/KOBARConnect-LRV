<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::insert([
            [
                "name"  => "Admin",
                "email" => "admin@gmail.com",
                "password" => Hash::make("12345678"),
                "id_status" => config('constant.STATUS_ARRAY')["ACTIVE"],
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ],
            [
                "name"  => "Abi Firmandhani",
                "email" => "abifirmandhani@gmail.com",
                "password" => Hash::make("12345678"),
                "id_status" => config('constant.STATUS_ARRAY')["ACTIVE"],
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ],
            [
                "name"  => "Suryadi Setyo",
                "email" => "setyo@gmail.com",
                "password" => Hash::make("12345678"),
                "id_status" => config('constant.STATUS_ARRAY')["ACTIVE"],
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ],
            [
                "name"  => "Nopik",
                "email" => "nopik@gmail.com",
                "password" => Hash::make("12345678"),
                "id_status" => config('constant.STATUS_ARRAY')["ACTIVE"],
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ],

        ]);

        $users = User::all();
        $roles = Role::where("name", "Administrator")->get();

        foreach($users as $user){
            $user->assignRole($roles);
        }
    }
}
