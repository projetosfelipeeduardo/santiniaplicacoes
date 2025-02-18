<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zip extends Model
{
    protected $fillable = [
        'arquivo',
        'hash'
    ];

    protected $table = 'zip';

    protected $guarded = ['id'];
}
