<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Halaman Laporan Keuangan dan Rekapitulasi Transaksi.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfDay()->format('Y-m-d'));

        $query = Transaction::with(['customer', 'package', 'user'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('package_id') && $request->package_id !== 'all') {
            $query->where('package_id', $request->package_id);
        }

        $transactions = $query->latest()->get();

        // Hitung total dan statistik
        $totalTransactions = $transactions->count();
        $totalIncome = $transactions->where('payment_status', 'lunas')->sum('total_price');
        $unpaidIncome = $transactions->where('payment_status', 'belum_lunas')->sum('total_price');
        $totalWeight = $transactions->sum('quantity');

        // Ringkasan per Layanan
        $packages = Package::all();
        $packageBreakdown = $packages->map(function ($pkg) use ($startDate, $endDate) {
            $pkgTransactions = Transaction::where('package_id', $pkg->id)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->get();

            return [
                'name' => $pkg->name,
                'price' => $pkg->price,
                'unit' => $pkg->unit,
                'count' => $pkgTransactions->count(),
                'quantity' => $pkgTransactions->sum('quantity'),
                'total_weight' => $pkgTransactions->sum('quantity'),
                'revenue' => $pkgTransactions->where('payment_status', 'lunas')->sum('total_price'),
                'total_revenue' => $pkgTransactions->where('payment_status', 'lunas')->sum('total_price'),
            ];
        });

        $packagePerformance = $packageBreakdown;

        $isPrint = $request->has('print');

        return view($isPrint ? 'reports.print' : 'reports.index', compact(
            'transactions',
            'totalTransactions',
            'totalIncome',
            'unpaidIncome',
            'totalWeight',
            'packageBreakdown',
            'packagePerformance',
            'packages',
            'startDate',
            'endDate'
        ));
    }
}
