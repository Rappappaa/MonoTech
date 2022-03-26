<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCode extends Model
{
    use HasFactory;

    protected $table = "promotion_code";

    protected $fillable = ['code','start_date','end_date','amount','quota'];

}
