<?php

namespace App\Services;

use App\Models\Transaction;
use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;
use Illuminate\Support\Traits\Localizable;

class InvoiceService {

    use Localizable;

    public function generate(Transaction $transaction): PDF
    {
        /** @var PDF $pdf */
        $pdf = $this->withLocale($transaction->user->language, fn () => PdfFacade::loadView('users.invoice', [
            'transaction' => $transaction,
        ]));

        $pdf->save($transaction->invoice_path, 'local');

        return $pdf->save($transaction->invoice_path, config('filesystems.default'));
    }
}
