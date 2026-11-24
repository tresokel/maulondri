<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $totalTransactions = Transaction::count();
        $processingCount = Transaction::whereIn('status', ['baru', 'proses'])->count();
        $readyCount = Transaction::where('status', 'selesai')->count();
        $completedCount = Transaction::where('status', 'diambil')->count();
        $totalIncome = Transaction::where('payment_status', 'lunas')->sum('total_price');
        $unpaidIncome = Transaction::where('payment_status', 'belum_lunas')->sum('total_price');
        $todayIncome = Transaction::where('payment_status', 'lunas')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');
        $customerCount = Customer::count();
        $packageCount = Package::count();

        // 5 Transaksi Terakhir
        $recentTransactions = Transaction::with(['customer', 'package', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Status counts for pipeline
        $statusCounts = [
            'baru' => Transaction::where('status', 'baru')->count(),
            'proses' => Transaction::where('status', 'proses')->count(),
            'selesai' => Transaction::where('status', 'selesai')->count(),
            'diambil' => Transaction::where('status', 'diambil')->count(),
        ];

        // Daftar layanan untuk quick overview
        $packages = Package::withCount('transactions')->get();

        return view('dashboard', compact(
            'totalTransactions',
            'processingCount',
            'readyCount',
            'completedCount',
            'totalIncome',
            'unpaidIncome',
            'todayIncome',
            'customerCount',
            'packageCount',
            'recentTransactions',
            'statusCounts',
            'packages'
        ));
    }
}
