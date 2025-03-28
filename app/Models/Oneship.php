<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oneship extends Model
{
    use HasFactory;
    protected $table = 'oneships';
    public $incrementing = false; 
    protected $primaryKey = 'e1_code';
    protected $keyType = 'string';
    protected $fillable = ['e1_code', 'release_date', 'chargeable_volumn', 'main_charge','receiver','recipient_address', 'phone_number', 'reference_number','file_name'];
    protected $attributes = ['carrier_id' =>1 ];
    public $timestamps = false;

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id','id');
    }
}