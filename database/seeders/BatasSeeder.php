<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatasSeeder extends Seeder
{
    public function run()
    {
        $batasWilayah = [
            "type" => "FeatureCollection",
            "features" => [
                [
                    "type" => "Feature",
                    "properties" => new \stdClass(), // kosong tapi tetap valid
                    "geometry" => [
                        "type" => "Polygon",
                        "coordinates" => [
                            [
                                [106.781855, -7.30414],
                                [106.781855, -7.106334],
                                [106.997182, -7.106334],
                                [106.997182, -7.30414],
                                [106.781855, -7.30414]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        DB::table('batas')->insert([
            'batas' => json_encode($batasWilayah),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
