<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "comments";
    protected $guarded = [];
    protected $appends = ['rates'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
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
