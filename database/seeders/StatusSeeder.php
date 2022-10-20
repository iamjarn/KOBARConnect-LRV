<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['name'=>'Active'],
            ['name'=>'Banned'],
            ['name'=>'Not Active'],
        ];
        DB::table('status')->insert($data);

    }
}
