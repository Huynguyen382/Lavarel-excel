<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrier extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code'];

    public function oneships()
    {
        return $this->hasMany(Oneship::class, 'carrier_id', 'id');
    }
    public function vnposts()
    {
        return $this->hasMany(vnpostModel::class, 'carrier_id', 'id');
    }
}
