<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_name',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'phone_number',
        'vat_number',
        'enable_invoicing',
        'invoice_prefix',
        'tax_name',
        'enable_tax',
        'tax_amount_type',
        'tax_amount',
        'tax_type',
        'enable_additional_tax',
        'additional_tax_name',
        'additional_tax_amount_type',
        'additional_tax_amount',
        'additional_tax_type',
    ];
}
