<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use App\Models\SellApplication;
use App\Models\BuyApplication;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_shareholders' => Shareholder::count(),
            'promoter_shareholders' => Shareholder::where('category', 'promoter')->count(),
            'pending_sell_applications' => SellApplication::where('status', 'pending')->count(),
            'pending_buy_applications' => BuyApplication::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'total_shares' => Shareholder::sum('share_quantity')
        ];

        $recent_sell_applications = SellApplication::with('seller')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_transactions = Transaction::with(['seller', 'buyApplication'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recent_sell_applications', 'recent_transactions'));
    }
}
