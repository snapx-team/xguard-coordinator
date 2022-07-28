<?php

namespace Xguard\Coordinator\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Xguard\Coordinator\Models\LocationPing;

class LocationPingsSeeder extends Seeder
{
    const LAT = 'lat';
    const LNG = 'lng';

    public function run()
    {
        $path = array (
            0 =>
                array (
                    self::LAT => 45.42084,
                    self::LNG => -73.65142,
                ),
            1 =>
                array (
                    self::LAT => 45.41813,
                    self::LNG => -73.64584,
                ),
            2 =>
                array (
                    self::LAT => 45.41617,
                    self::LNG => -73.64129,
                ),
            3 =>
                array (
                    self::LAT => 45.41581,
                    self::LNG => -73.6352,
                ),
            4 =>
                array (
                    self::LAT => 45.41572,
                    self::LNG => -73.63133,
                ),
            5 =>
                array (
                    self::LAT => 45.41536,
                    self::LNG => -73.62476,
                ),
            6 =>
                array (
                    self::LAT => 45.41568,
                    self::LNG => -73.61893,
                ),
            7 =>
                array (
                    self::LAT => 45.41628,
                    self::LNG => -73.61262,
                ),
            8 =>
                array (
                    self::LAT => 45.41845,
                    self::LNG => -73.60768,
                ),
            9 =>
                array (
                    self::LAT => 45.42107,
                    self::LNG => -73.60283,
                ),
            10 =>
                array (
                    self::LAT => 45.42387,
                    self::LNG => -73.59931,
                ),
            11 =>
                array (
                    self::LAT => 45.42775,
                    self::LNG => -73.59523,
                ),
            12 =>
                array (
                    self::LAT => 45.4311,
                    self::LNG => -73.59132,
                ),
            13 =>
                array (
                    self::LAT => 45.43435,
                    self::LNG => -73.58703,
                ),
            14 =>
                array (
                    self::LAT => 45.43814,
                    self::LNG => -73.5824,
                ),
            15 =>
                array (
                    self::LAT => 45.44371,
                    self::LNG => -73.57651,
                ),
            16 =>
                array (
                    self::LAT => 45.4478,
                    self::LNG => -73.57106,
                ),
            17 =>
                array (
                    self::LAT => 45.45159,
                    self::LNG => -73.56763,
                ),
            18 =>
                array (
                    self::LAT => 45.45487,
                    self::LNG => -73.56523,
                ),
            19 =>
                array (
                    self::LAT => 45.45854,
                    self::LNG => -73.56325,
                ),
            20 =>
                array (
                    self::LAT => 45.45854,
                    self::LNG => -73.56352,
                ),
            21 =>
                array (
                    self::LAT => 45.45855,
                    self::LNG => -73.56363,
                ),
            22 =>
                array (
                    self::LAT => 45.45854,
                    self::LNG => -73.5636,
                ),
            23 =>
                array (
                    self::LAT => 45.45854,
                    self::LNG => -73.56357,
                ),
            24 =>
                array (
                    self::LAT => 45.45857,
                    self::LNG => -73.56373,
                ),
            25 =>
                array (
                    self::LAT => 45.45855,
                    self::LNG => -73.56376,
                ),
            26 =>
                array (
                    self::LAT => 45.45854,
                    self::LNG => -73.56369,
                ),
            27 =>
                array (
                    self::LAT => 45.45852,
                    self::LNG => -73.56355,
                ),
            28 =>
                array (
                    self::LAT => 45.45852,
                    self::LNG => -73.56363,
                ),
            29 =>
                array (
                    self::LAT => 45.45866,
                    self::LNG => -73.56344,
                ),
            30 =>
                array (
                    self::LAT => 45.45859,
                    self::LNG => -73.56336,
                ),
            31 =>
                array (
                    self::LAT => 45.45857,
                    self::LNG => -73.56336,
                ),
            32 =>
                array (
                    self::LAT => 45.45854,
                    self::LNG => -73.56337,
                ),
            33 =>
                array (
                    self::LAT => 45.45944,
                    self::LNG => -73.56321,
                ),
            34 =>
                array (
                    self::LAT => 45.46144,
                    self::LNG => -73.56291,
                ),
            35 =>
                array (
                    self::LAT => 45.46262,
                    self::LNG => -73.56349,
                ),
            36 =>
                array (
                    self::LAT => 45.4628,
                    self::LNG => -73.56699,
                ),
            37 =>
                array (
                    self::LAT => 45.46289,
                    self::LNG => -73.56952,
                ),
            38 =>
                array (
                    self::LAT => 45.46298,
                    self::LNG => -73.5713,
                ),
            39 =>
                array (
                    self::LAT => 45.46155,
                    self::LNG => -73.57141,
                ),
            40 =>
                array (
                    self::LAT => 45.45976,
                    self::LNG => -73.57162,
                ),
            41 =>
                array (
                    self::LAT => 45.45821,
                    self::LNG => -73.57177,
                ),
            42 =>
                array (
                    self::LAT => 45.45619,
                    self::LNG => -73.57197,
                ),
            43 =>
                array (
                    self::LAT => 45.45502,
                    self::LNG => -73.57207,
                ),
            44 =>
                array (
                    self::LAT => 45.45481,
                    self::LNG => -73.57216,
                ),
            45 =>
                array (
                    self::LAT => 45.45494,
                    self::LNG => -73.57441,
                ),
            46 =>
                array (
                    self::LAT => 45.45499,
                    self::LNG => -73.57632,
                ),
            47 =>
                array (
                    self::LAT => 45.45428,
                    self::LNG => -73.57648,
                ),
            48 =>
                array (
                    self::LAT => 45.453,
                    self::LNG => -73.5766,
                ),
            49 =>
                array (
                    self::LAT => 45.4527,
                    self::LNG => -73.57663,
                ),
            50 =>
                array (
                    self::LAT => 45.45272,
                    self::LNG => -73.57783,
                ),
            51 =>
                array (
                    self::LAT => 45.45282,
                    self::LNG => -73.58027,
                ),
            52 =>
                array (
                    self::LAT => 45.45284,
                    self::LNG => -73.58171,
                ),
            53 =>
                array (
                    self::LAT => 45.45276,
                    self::LNG => -73.58183,
                ),
            54 =>
                array (
                    self::LAT => 45.45271,
                    self::LNG => -73.58187,
                ),
            55 =>
                array (
                    self::LAT => 45.45269,
                    self::LNG => -73.58188,
                ),
            56 =>
                array (
                    self::LAT => 45.45265,
                    self::LNG => -73.58184,
                ),
            57 =>
                array (
                    self::LAT => 45.45264,
                    self::LNG => -73.58178,
                ),
            58 =>
                array (
                    self::LAT => 45.45269,
                    self::LNG => -73.58168,
                ),
            59 =>
                array (
                    self::LAT => 45.45268,
                    self::LNG => -73.58192,
                ),
            60 =>
                array (
                    self::LAT => 45.45265,
                    self::LNG => -73.58189,
                ),
            61 =>
                array (
                    self::LAT => 45.45263,
                    self::LNG => -73.58184,
                ),
            62 =>
                array (
                    self::LAT => 45.45293,
                    self::LNG => -73.58181,
                ),
            63 =>
                array (
                    self::LAT => 45.45286,
                    self::LNG => -73.58162,
                ),
        );

        $currentTime =  Carbon::now();

        foreach ($path as $ping)

        {
            $currentTime =  $currentTime->addSeconds(30);
            LocationPing::create([
                LocationPing::SUPERVISOR_SHIFT_ID => 9,
                LocationPing::LAT => $ping[self::LAT],
                LocationPing::LNG => $ping[self::LNG],
                LocationPing::CREATED_AT => $currentTime

            ]);
        }
    }
}
