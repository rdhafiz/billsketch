<?php

namespace App\Models;

use App\Constants\InvoiceRecurringStatus;
use App\Constants\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoices extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
    ];

    protected $casts = [
    ];

    public function invoice_items()
    {
        return $this->hasMany(RecurringInvoiceItems::class, 'invoice_id', 'id')->select('id', 'invoice_id', 'description', 'unit_frequency', 'unit_value', 'total');
    }
    public function category()
    {
        return $this->hasOne(Categories::class, 'id', 'category_id')->select('id', 'name', 'color', 'icon');
    }
    public function client()
    {
        return $this->hasOne(Clients::class, 'id', 'client_id')->select('id', 'name', 'logo');
    }
    public function employee()
    {
        return $this->hasOne(Employees::class, 'id', 'employee_id')->select('id', 'name', 'avatar');
    }
}
