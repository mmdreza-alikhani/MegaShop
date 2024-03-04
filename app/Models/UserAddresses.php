<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    use HasFactory;
    protected $table = "user_addresses";
    protected $guarded = [];
}
