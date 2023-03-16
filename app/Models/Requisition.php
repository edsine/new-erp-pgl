<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'requisition_date',
        'document',
        'status',
        'title',
        'ref_number'
       
    ];
    

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function totalAmount() {
        $total_amount = 0;
        $id = $this->id;
        $items = RequisitionItem::where('requisition_id', $id)->get();

        foreach($items as $item) {
            $total_amount += ($item['quantity'] * $item['rate']);
        }

        return $total_amount;
    }
    public function requisitionItem()
    {
        return $this->hasMany('App\Models\RequisitionItem');
    }
}
