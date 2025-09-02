<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperTransaction
 */
class Transaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'datetime',
        'amount' => MoneyCast::class,
        'tax' => MoneyCast::class,
        'fee' => MoneyCast::class,
    ];

    public $timestamps = false;

    public const string INVOICE_FOLDER = 'invoices';

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription() : BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    protected function amountWithoutTax(): Attribute
    {
        return Attribute::make(
            get: fn () => (new MoneyCast())
                ->get($this, 'amount_without_tax',  $this->getRawOriginal('amount') - $this->getRawOriginal('tax'), ['country' => $this->country])
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
