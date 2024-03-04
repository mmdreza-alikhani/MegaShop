<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "comments";
    protected $guarded = [];
    protected $appends = ['rates'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function news(){
        return $this->belongsTo(News::class);
    }

    public function rates(){
        return $this->belongsTo(ProductRate::class);
    }

    public function getApprovedAttribute($approved){
        return $approved ? 'منتشر شده' : 'در حال بررسی';
    }

    public function reply_for(){
        return $this->belongsTo(Comment::class, 'reply_of');
    }

    public function child(){
        return $this->hasMany(Comment::class, 'reply_of');
    }

}
