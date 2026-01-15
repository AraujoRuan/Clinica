<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'stats' => $this->getStats(),
            'upcomingAppointments' => $this->getUpcomingAppointments(),
            'recentPatients' => $this->getRecentPatients(),
            'financialOverview' => $this->getFinancialOverview(),
        ];

        return view('admin.dashboard', $data);
    }

    private function getStats()
    {
        $today = Carbon::today();
        
        return [
            'totalPatients' => Patient::count(),
            'activePatients' => Patient::where('status', 'active')->count(),
            'todayAppointments' => Appointment::whereDate('start_time', $today)->count(),
            'pendingInvoices' => Invoice::where('status', 'pending')->count(),
            'monthRevenue' => Invoice::where('status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->sum('total_amount'),
        ];
    }

    private function getUpcomingAppointments()
    {
        return Appointment::with(['patient', 'professional'])
            ->where('status', 'scheduled')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->limit(10)
            ->get();
    }

    private function getRecentPatients()
    {
        return Patient::with('professional')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    }

    private function getFinancialOverview()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        return [
            'monthlyRevenue' => Invoice::where('status', 'paid')
                ->whereMonth('payment_date', $currentMonth)
                ->whereYear('payment_date', $currentYear)
                ->sum('total_amount'),
            'pendingAmount' => Invoice::where('status', 'pending')
                ->sum('total_amount'),
            'expensesThisMonth' => \App\Models\Expense::where('status', 'paid')
                ->whereMonth('payment_date', $currentMonth)
                ->sum('amount'),
        ];
    }
}