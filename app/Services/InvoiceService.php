<?php

namespace App\Services;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService {

    public function generate(Transaction $transaction)
    {
        $pdf = Pdf::loadView('users.invoice', [
            'transaction' => $transaction
        ]);

        return $pdf->save($transaction->invoice_path, 'invoices');
    }
}
