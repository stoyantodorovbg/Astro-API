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
            'bj'    => ['julianDate'],
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
            'date'                   => 'bj',
            'planets'                => 'p',
            'houseTypes'             => 'house',
            'sidereal'               => 'sid',
            'heliacal_events'        => 'hev',
            'location'               => 'topo',
            'heliocentric_positions' => 'hel',
            'solar_eclipse'          => 'solecl',
            'occultation_by_moon'    => 'occult',
            'lunar_eclipse'          => 'lunecl',
            'rising_and_setting'     => 'rise',
            'total_eclipse'          => 'total',
            'partial_eclipse'        => 'partial',
            'star'                   => 'xf',
            'coordinate'             => '',
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
                'mean_lunar_node'       => 'm',
                'true_lunar_node'       => 't',
                'nutation'              => 'n',
                'obliquity_of_ecliptic' => 'o',
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
