<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'department_id',
        'requisition_date',
        'document',
        'status',
        'title',
        'ref_number',
        'created_by',
        'hod_appproval',
        'admin_approval',
        'chairman_approval',
        'hod_remark',
        'admin_remark',
        'chairman_remark',
        'payment_status',
       
    ];
    

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id')->first();
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
