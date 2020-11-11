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
            'b'     => 'julianDate',
            'p'     => 'planets',
            'house' => 'coordinates,houseTypes',
            'sid'   => 'siderealMethods',
            'topo'  => 'coordinates,elevation',
            'hev'   => 'heliacalEvents'
        ]
    ]


];
