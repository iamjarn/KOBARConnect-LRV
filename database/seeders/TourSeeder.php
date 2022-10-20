<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        for ($j=0; $j < 10; $j++) {
            $data[] = [
                "name"  => "Labuan Bajo",
                "description"   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi rhoncus imperdiet felis sit amet mollis. Aenean euismod id eros id elementum. In condimentum, ex sit amet convallis accumsan, felis nisi euismod turpis",
                "id_category"   => 1,
                "latitude"      => -7.951278,
                "longitude"     => 112.6317837,
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ];
        }
        DB::table("tour_places")->insert($data);
    }
}
