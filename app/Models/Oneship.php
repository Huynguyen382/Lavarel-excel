<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oneship extends Model
{
    use HasFactory;
    public $incrementing = false; 
    protected $table = 'oneships';
    protected $fillable = ['e1_code', 'release_date', 'chargeable_volumn', 'main_charge','receiver','recipient_address', 'phone_number', 'reference_number'];
    public $timestamps = false;
}
