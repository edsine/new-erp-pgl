<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'requisition_id',
        'item',
        'quantity',
        'rate',
        'amount',
        'ref_number',
    ];

    public function requisition(){
        return $this->belongsTo('App\Models\Requisition', 'requisition_id');
    }
}
