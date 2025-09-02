<?php

namespace App\Http\Controllers\Admin;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;

class InvoiceController
{
    public function index () : View {
        return view('admin.invoices.index', [
            'invoices' => Transaction::query()
                ->with('user')
                ->latest('date')
                ->paginate()
        ]);
    }
}
