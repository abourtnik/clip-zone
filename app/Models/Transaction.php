<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'datetime'
    ];

    public $timestamps = false;

    public const INVOICE_FOLDER = 'invoices';

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription() : BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    protected function formatedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount/ 100, 2)
        );
    }

    protected function amountWithoutTax(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount - $this->tax
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('user.invoices.show', $this)
        );
    }

    protected function invoicePath(): Attribute
    {
        return Attribute::make(
            get: fn () => self::INVOICE_FOLDER.'/'.$this->date->format('Y-m').'/'.$this->invoice_name
        );
    }

    protected function invoiceName(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Invoice' . $this->id . '-' .$this->date->format('Y-m-d').'.pdf'
        );
    }

    protected function invoiceUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::path($this->invoice_path)
        );
    }
}
