<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController
{
    public function index(): View {

        return view('users.invoices.index', [
            'invoices' => Transaction::where('user_id' , Auth::user()->id)
                ->latest('date')
                ->paginate()
        ]);
    }

    public function show(Transaction $transaction) : Response {

        $file = Storage::get($transaction->invoice_path);

        return response($file)
            ->header('Content-Type', 'application/pdf');
    }
}
