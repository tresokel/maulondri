<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with(['customer', 'package', 'user']);

        // Filter status cucian
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter status pembayaran
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter pencarian invoice, nama pelanggan, nomor hp
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('package', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        // Status counts for tabs
        $allCount = Transaction::count();
        $baruCount = Transaction::where('status', 'baru')->count();
        $prosesCount = Transaction::where('status', 'proses')->count();
        $selesaiCount = Transaction::where('status', 'selesai')->count();
        $diambilCount = Transaction::where('status', 'diambil')->count();

        return view('transactions.index', compact(
            'transactions',
            'allCount',
            'baruCount',
            'prosesCount',
            'selesaiCount',
            'diambilCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::orderBy('price')->get();

        return view('transactions.create', compact('customers', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_type' => 'nullable|in:existing,new',
            'customer_id' => 'required_without:new_customer_name|nullable|exists:customers,id',
            'new_customer_name' => 'required_if:customer_type,new|nullable|string|max:255',
            'new_customer_phone' => 'required_if:customer_type,new|nullable|string|max:25',
            'new_customer_address' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|numeric|min:0.1',
            'payment_status' => 'required|in:belum_lunas,lunas',
            'payment_method' => 'nullable|in:Tunai,QRIS,Transfer',
            'notes' => 'nullable|string',
        ]);

        // Tentukan customer
        if ($request->customer_type === 'new' && $request->filled('new_customer_name')) {
            $customer = Customer::create([
                'name' => $request->new_customer_name,
                'phone' => $request->new_customer_phone,
                'address' => $request->new_customer_address,
            ]);
            $customerId = $customer->id;
        } else {
            $customerId = $request->customer_id;
        }

        $package = Package::findOrFail($request->package_id);
        $totalPrice = (int) round($request->quantity * $package->price);

        $invoiceCode = Transaction::generateInvoiceCode();

        $transaction = Transaction::create([
            'invoice_code' => $invoiceCode,
            'user_id' => Auth::id(),
            'customer_id' => $customerId,
            'package_id' => $request->package_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'baru',
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method ?? 'Tunai',
            'notes' => $request->notes,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): View
    {
        $transaction->load(['customer', 'package', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        $transaction->load(['customer', 'package', 'user']);
        $packages = Package::all();

        return view('transactions.edit', compact('transaction', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:baru,proses,selesai,diambil',
            'payment_status' => 'required|in:belum_lunas,lunas',
            'payment_method' => 'nullable|in:Tunai,QRIS,Transfer',
            'notes' => 'nullable|string',
        ]);

        $transaction->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method ?? $transaction->payment_method ?? 'Tunai',
            'notes' => $request->notes,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Status transaksi '.$transaction->invoice_code.' berhasil diperbarui!');
    }

    /**
     * Quick status update (e.g. from proses to selesai).
     */
    public function quickStatus(Request $request, Transaction $transaction): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:baru,proses,selesai,diambil',
        ]);

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Status transaksi '.$transaction->invoice_code.' diubah menjadi '.ucfirst($request->status));
    }

    /**
     * Quick payment update.
     */
    public function quickPayment(Transaction $transaction): RedirectResponse
    {
        $newPaymentStatus = $transaction->payment_status === 'lunas' ? 'belum_lunas' : 'lunas';
        $transaction->update(['payment_status' => $newPaymentStatus]);

        return back()->with('success', 'Status pembayaran '.$transaction->invoice_code.' berhasil diubah menjadi '.($newPaymentStatus === 'lunas' ? 'Lunas' : 'Belum Lunas'));
    }

    /**
     * Cetak Struk / Nota Transaksi.
     */
    public function printReceipt(Transaction $transaction): View
    {
        $transaction->load(['customer', 'package', 'user']);

        return view('transactions.receipt', compact('transaction'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $invoice = $transaction->invoice_code;
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi '.$invoice.' berhasil dihapus!');
    }
}
