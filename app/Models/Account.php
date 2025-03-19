<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_account'; 
    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    // public function setPasswordAttribute($value)
    // {
        // $this->attributes['password'] = Hash::make($value);

        // if (!empty($value) && !Hash::needsRehash($value)) {
        //     $this->attributes['password'] = Hash::make($value);
        // } else {
        //     $this->attributes['password'] = $value;
        // }
    // }
    
}

