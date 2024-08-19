<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'cate_id',
        'post_id',
    ];
}
