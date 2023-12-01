<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'invoice_date_formatted',
        'invoice_due_date_formatted',
        'recurring_end_date_formatted',
        'created_at_formatted',
        'updated_at_formatted',
        'invoice_item_headings_formatted',
    ];

    protected $casts = [
//        'invoice_item_headings' => 'json',
        'invoice_date' => 'datetime',
        'invoice_due_date' => 'datetime',
        'recurring_end_date' => 'datetime',
    ];

    public function getInvoiceItemHeadingsFormattedAttribute()
    {
        if (!empty($this->invoice_item_headings)) {
            return json_decode($this->invoice_item_headings);
        }
        return null;
    }
    public function getInvoiceDateFormattedAttribute()
    {
        if (!empty($this->invoice_date)) {
            return $this->invoice_date->format('d/m/Y');
        }
        return null;
    }
    public function getInvoiceDueDateFormattedAttribute()
    {
        if (!empty($this->invoice_due_date)) {
            return $this->invoice_due_date->format('d/m/Y');
        }
        return null;
    }
    public function getRecurringEndDateFormattedAttribute()
    {
        if (!empty($this->recurring_end_date)) {
            return $this->recurring_end_date->format('d/m/Y');
        }
        return null;
    }
    public function getCreatedAtFormattedAttribute()
    {
        if (!empty($this->created_at)) {
            return $this->created_at->format('d/m/Y');
        }
        return null;
    }
    public function getUpdatedAtFormattedAttribute()
    {
        if (!empty($this->updated_at)) {
            return $this->updated_at->format('d/m/Y');
        }
        return null;
    }
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id', 'id')->select('id', 'invoice_id', 'description', 'unit_frequency', 'unit_value', 'total');
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
