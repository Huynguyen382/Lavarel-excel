<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class vnpostModel extends Model
{
    use HasFactory;
    public $incrementing = false; 
    protected $table = 'vnpost';
    protected $primaryKey = 'e1_code';
    protected $fillable = ['e1_code', 'release_date', 'chargeable_volumn', 'main_charge','receiver','recipient_address', 'phone_number', 'reference_number','file_name'];
    public $timestamps = false;
}