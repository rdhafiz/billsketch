<?php

namespace App\Models;

use App\Constants\InvoiceRecurringStatus;
use App\Constants\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $appends = [
        'invoice_date_formatted',
        'invoice_due_date_formatted',
        'recurring_end_date_formatted',
        'created_at_formatted',
        'updated_at_formatted',
        'invoice_item_headings_formatted',
        'qrcode_path',
        'recurring_frequency_name',
        'invoice_status_name',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'invoice_due_date' => 'datetime',
        'recurring_end_date' => 'datetime',
    ];

    public function getQrcodePathAttribute()
    {
        if (!empty($this->qrcode)) {
            return asset('storage/uploads/'.$this->qrcode);
        }
        return null;
    }
    public function getRecurringFrequencyNameAttribute()
    {
        if (!empty($this->recurring_frequency)) {
            return InvoiceRecurringStatus::getMapValue($this->recurring_frequency);
        }
        return null;
    }
    public function getInvoiceStatusNameAttribute()
    {
        if (!empty($this->invoice_status)) {
            return InvoiceStatus::getMapValue($this->invoice_status);
        }
        return null;
    }
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
    public function payee()
    {
        return $this->hasOne(Payees::class, 'id', 'payee_id')->select('id', 'name', 'avatar');
    }

    public static function generateInvoiceNumber($prefix, $number){
        $invoiceNumber = str_pad($number, 6, '0', STR_PAD_LEFT);
        $invoiceNumber = $prefix.'-'.$invoiceNumber;
        return $invoiceNumber;
    }

}
