<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'Alam'],
            ['name'=>'Religi'],
            ['name'=>'Kuliner'],
            ['name'=>'Edukasi'],
            ['name'=>'Sejarah']
        ];
        DB::table('category')->insert($data);
    }
}
