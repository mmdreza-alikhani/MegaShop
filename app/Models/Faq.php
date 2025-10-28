<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';
    protected $fillable = [
        'question',
        'answer',
        'faqable_type',
        'faqable_id',
    ];
    protected $casts = [
        'question' => 'string',
        'answer' => 'string',
        'faqable_type' => 'string',
        'faqable_id' => 'integer',
    ];

}
