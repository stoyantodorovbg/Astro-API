<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ['key', 'data'];

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }
}
