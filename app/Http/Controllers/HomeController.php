<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Expense;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::find(session('user_id'));
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Calculate budget metrics
        $totalBudget = $user->getTotalBudget();
        $savings = $user->income * $user->savings_percentage / 100;

        // Get total spent this month
        $totalSpent = Expense::where('user_id', $user->id)
            ->forMonth($currentMonth, $currentYear)
            ->sum('amount');

        $remainingBudget = $totalBudget - $totalSpent;

        // Calculate category spending
        $categorySpending = [];
        $categoryBudgets = $user->getAllCategoryBudgets();
        
        foreach (['transportation', 'food', 'home_utilities', 'entertainment'] as $cat) {
            $spent = Expense::where('user_id', $user->id)
                ->forMonth($currentMonth, $currentYear)
                ->where('category', $cat)
                ->sum('amount');
            
            $categorySpending[$cat] = [
                'spent' => $spent,
                'budget' => $categoryBudgets[$cat],
                'remaining' => $categoryBudgets[$cat] - $spent,
                'percentage' => $categoryBudgets[$cat] > 0 ? ($spent / $categoryBudgets[$cat]) * 100 : 0,
            ];
        }

        // Get recent expenses (last 5)
        $recentExpenses = Expense::where('user_id', $user->id)
            ->orderBy('expense_date', 'desc')
            ->take(5)
            ->get();

        // Prepare chart data - last 6 months
        $chartData = $this->getChartData($user->id);

        return view('home', compact(
            'user',
            'totalBudget',
            'savings',
            'totalSpent',
            'remainingBudget',
            'categorySpending',
            'recentExpenses',
            'chartData'
        ));
    }

    private function getChartData($userId)
    {
        $months = [];
        $labels = [];
        $data = [
            'transportation' => [],
            'food' => [],
            'home_utilities' => [],
            'entertainment' => [],
        ];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->month,
                'year' => $date->year,
            ];
            $labels[] = $date->format('M Y');
        }

        // Get spending data for each category and month
        foreach ($months as $monthData) {
            foreach (['transportation', 'food', 'home_utilities', 'entertainment'] as $category) {
                $spent = Expense::where('user_id', $userId)
                    ->whereMonth('expense_date', $monthData['month'])
                    ->whereYear('expense_date', $monthData['year'])
                    ->where('category', $category)
                    ->sum('amount');
                
                $data[$category][] = $spent;
            }
        }

        return [
            'labels' => $labels,
            'transportation' => $data['transportation'],
            'food' => $data['food'],
            'home_utilities' => $data['home_utilities'],
            'entertainment' => $data['entertainment'],
        ];
    }
}