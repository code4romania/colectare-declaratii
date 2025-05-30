<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    protected $fillable =[
        'county_id',
        'locality_id',
        'official_id',
        'type',
        'official_name',
        'institution',
        'position',
        'file',
        'ip_address',
    ];

}
