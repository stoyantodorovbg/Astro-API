<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Validations for the Swetest commands
    |--------------------------------------------------------------------------
    */
    'validations' => [
        'options' => [
            'b'     => ['julianDate'],
            'p'     => ['planets'],
            'house' => ['coordinate', 'coordinate', 'houseTypes'],
            'sid'   => ['siderealMethods'],
            'topo'  => ['coordinate', 'coordinate', 'elevation'],
            'hev'   => ['heliacalEvents']
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Connections between HTTP and Swetest
    |--------------------------------------------------------------------------
    */
    'httpMapping' => [
        'optionsKeys'                => [
            'date'                   => 'b',
            'planets'                => 'p',
            'houses'                 => 'house',
            'sidereal'               => 'sid',
            'heliacal-events'        => 'hev',
            'location'               => 'topo',
            'heliocentric positions' => 'hel',
            'solar-eclipse'          => 'solecl',
            'occultation-by-moon'    => 'occult',
            'lunar-eclipse'          => 'lunecl',
            'rising-and-setting'     => 'rise',
            'total-eclipse'          => 'total',
            'partial-eclipse'        => 'partial',
            'star'                   => 'xf'
        ],
        'optionsValues' => [
            'planets' => [
                'all'                   => 'd',
                'sun'                   => '0',
                'moon'                  => '1',
                'mercury'               => '2',
                'venus'                 => '3',
                'mars'                  => '4',
                'jupiter'               => '5',
                'saturn'                => '6',
                'uranus'                => '7',
                'neptune'               => '8',
                'pluto'                 => '9',
                'mean-lunar-node'       => 'm',
                'true-lunar-node'       => 't',
                'nutation'              => 'n',
                'obliquity-of-ecliptic' => 'o',
                'lilit'                 => 'A',
                'earth'                 => 'C',
                'ceres'                 => 'F',
                'chiron'                => 'D',
                'pholus'                => 'E',
                'pallas'                => 'G',
                'juno'                  => 'H',
                'vesta'                 => 'I',
                'star'                  => 'f',
                'sidereal-time'         => 'x',
            ],
            'houseTypes' => [
                'equal'            => 'A',
                'equal-a'          => 'E',
                'equal-whole-sign' => 'W',
                'alcabitius'       => 'B',
                'horizon'          => 'H',
                'sunshine'         => 'I',
                'koch'             => 'K',
                'morinus'          => 'M',
                'placidus'         => 'P',
                'regiomontanus'    => 'R',

            ],
            'siderealMethods' => [
                'Babylonian-15-tau'             => '14',
                'valens'                        => '42',
                'galactic-equator'              => '32',
                'galactic-center-0-sagittarius' => '17',
            ]
        ],
    ],


];
