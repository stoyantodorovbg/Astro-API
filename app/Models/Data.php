<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    /**
     * @var string[]
     */
    protected $fillable = ['key', 'data'];

    /**
     * Do nothing.
     *
     * @param mixed $value
     * @return Data|void
     */
    public function setUpdatedAt($value)
    {
    }
}
