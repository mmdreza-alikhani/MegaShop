<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
