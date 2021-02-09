<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeliacalEvent extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'expected_at',
        'visible_for',
        'planet_id',
        'type_id',
        'geopoint_id'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
