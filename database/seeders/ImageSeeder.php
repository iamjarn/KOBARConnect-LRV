<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tours = DB::table("tour_places")->get();

        for ($i=0; $i < count($tours); $i++) {
            DB::table("tour_place_image")->insert([
                "path"  => "/media/tours/demo.jpeg",
                "id_tour_place" => $tours[$i]->id,
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ]);
        }
    }
}
