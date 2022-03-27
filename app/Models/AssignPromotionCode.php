<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignPromotionCode extends Model
{
    use HasFactory;

    protected $table = "assign_promotion";

    protected $fillable = ['user_id','promotion_code_id'];

    public function promotionCode()
    {
        return $this->hasOne(PromotionCode::class,'id','promotion_code_id');
    }
}
