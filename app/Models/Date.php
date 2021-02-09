<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['date'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
