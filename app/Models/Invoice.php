<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'invoice_number','user_id','description','quantity','rate','amount','tax_id','total','payment_method','remarks','invoice_status_id','date'
    ];

    public function invoice_status(): BelongsTo {
        return $this->belongsTo(InvoiceStatus::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function tax(): BelongsTo {
        return $this->belongsTo(Tax::class);
    }
}
