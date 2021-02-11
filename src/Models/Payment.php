<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'payment_paypal_id',
        'date',
        'status',
        'value',
        'info',
    ];

    protected $dates = ['deleted_at'];
}
