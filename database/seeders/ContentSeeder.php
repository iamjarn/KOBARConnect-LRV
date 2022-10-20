<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use Carbon\Carbon;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                'path' => '/uploads/content/demo.mp4',
                'order' => 1,
                'file_type' => config('constant.FILE_TYPE')["VIDEO"],
                'message'   => 'Di era Majapahit Abad XII-Abad XIV Masehi Pasuruan merupakan nama tempat hunian masyarakat yang tertulis dalam Kitab Negara Kertagama karangan Empu Prapanca. Pasoeroean dari segi kebahasaan dapat diurai menjadi pa-soeroe-an artinya tempat tumbuh tanaman suruh atau kumpulan daun suruh.',
                'created_at' => Carbon::now()
            ],
            [
                'path' => '/uploads/content/demo1.jpeg',
                'order' => 2,
                'file_type' => config('constant.FILE_TYPE')["IMAGE"],
                'message'   => 'Di era Majapahit Abad XII-Abad XIV Masehi Pasuruan merupakan nama tempat hunian masyarakat yang tertulis dalam Kitab Negara Kertagama karangan Empu Prapanca. Pasoeroean dari segi kebahasaan dapat diurai menjadi pa-soeroe-an artinya tempat tumbuh tanaman suruh atau kumpulan daun suruh.',
                'created_at' => Carbon::now()
            ],
            [
                'path' => '/uploads/content/demo2.png',
                'file_type' => config('constant.FILE_TYPE')["IMAGE"],
                'order' => 3,
                'message'   => 'Di era Majapahit Abad XII-Abad XIV Masehi Pasuruan merupakan nama tempat hunian masyarakat yang tertulis dalam Kitab Negara Kertagama karangan Empu Prapanca. Pasoeroean dari segi kebahasaan dapat diurai menjadi pa-soeroe-an artinya tempat tumbuh tanaman suruh atau kumpulan daun suruh.',
                'created_at' => Carbon::now()
            ],
        ];
        // dd($data);
        Content::insert($data);
    }
}
