<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function PHPUnit\Framework\returnArgument;

class ProductVariation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "product_variations";
    protected $guarded = [];
    protected $appends = ['is_sale','sale_percent'];

    public function getIsSaleAttribute()
    {
        return ($this->sale_price =! null && $this->date_on_sale_from < Carbon::now() && $this->date_on_sale_to > Carbon::now()) ? true : false;
    }

    public function getSalePercentAttribute()
    {
        return $this->is_sale ? round((($this->price - $this->sale_price) / $this->price) * 100) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
