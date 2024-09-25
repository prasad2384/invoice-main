<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['company_name','lut','company_ad dress','gst_number','trade_name','update_logo','update_logo_icon'];
}
