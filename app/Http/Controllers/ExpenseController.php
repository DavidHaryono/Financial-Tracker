<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\User;
use App\Services\ReceiptParserService;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    protected $receiptParser;

    public function __construct(ReceiptParserService $receiptParser)
    {
        $this->receiptParser = $receiptParser;
    }

    // show all expenses with filters
    public function index(Request $request)
    {
        $user = User::find(session('user_id'));
        
        // Get filter parameters or use current month/year
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $category = $request->input('category', 'all');

        // Get expenses with filters
        $expenses = Expense::where('user_id', session('user_id'))
            ->forMonth($month, $year)
            ->byCategory($category)
            ->orderBy('expense_date', 'desc')
            ->get();

        // Calculate spending by category for the selected month
        $categorySpending = [];
        $categoryBudgets = $user->getAllCategoryBudgets();
        $categoryPercentages = $user->getCategoryPercentages();

        
        foreach (['transportation', 'food', 'home_utilities', 'entertainment'] as $cat) {
            $spent = Expense::where('user_id', session('user_id'))
                ->forMonth($month, $year)
                ->where('category', $cat)
                ->sum('amount');
            
            $categorySpending[$cat] = [
                'spent' => $spent,
                'budget' => $categoryBudgets[$cat],
                'remaining' => $categoryBudgets[$cat] - $spent,
                'percentage' => $categoryBudgets[$cat] > 0 ? ($spent / $categoryBudgets[$cat]) * 100 : 0,
            ];
        }

        // Total budget and spending
        $totalBudget = $user->getTotalBudget();
        $totalSpent = array_sum(array_column($categorySpending, 'spent'));
        $totalRemaining = $totalBudget - $totalSpent;

        return view('expenses.index', compact(
            'expenses', 
            'user', 
            'categorySpending', 
            'totalBudget', 
            'totalSpent', 
            'totalRemaining',
            'month',
            'year',
            'category',
            'categoryPercentages'
        ));
    }

    // show form
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Parse receipt and return extracted data
     */
    public function parseReceipt(Request $request)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        try {
            // Store the file temporarily
            $file = $request->file('receipt');
            $path = $file->store('temp_receipts', 'public');
            $fullPath = storage_path('app/public/' . $path);

            // Parse the receipt
            $result = $this->receiptParser->parseReceipt($fullPath);

            // Keep the file for later use (we'll move it when the form is submitted)
            return response()->json([
                'success' => $result['success'],
                'data' => [
                    'title' => $result['title'] ?? '',
                    'category' => $result['category'] ?? '',
                    'amount' => $result['amount'] ?? 0,
                ],
                'temp_path' => $path,
                'message' => $result['error'] ?? 'Receipt parsed successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing receipt: ' . $e->getMessage(),
            ], 500);
        }
    }

    // save new expense
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'category' => 'required|in:transportation,food,home_utilities,entertainment',
            'amount' => 'required|integer|min:0',
            'receipt' => 'nullable|file|max:10240', // 10MB max
            'temp_receipt_path' => 'nullable|string', // For already uploaded receipts
            'expense_date' => 'required|date',
        ]);

        // Handle receipt file
        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        } elseif ($request->filled('temp_receipt_path')) {
            // Move from temp location to permanent location
            $tempPath = $request->input('temp_receipt_path');
            $fullTempPath = storage_path('app/public/' . $tempPath);
            
            if (file_exists($fullTempPath)) {
                $newPath = 'receipts/' . basename($tempPath);
                $fullNewPath = storage_path('app/public/' . $newPath);
                
                // Create receipts directory if it doesn't exist
                if (!file_exists(dirname($fullNewPath))) {
                    mkdir(dirname($fullNewPath), 0755, true);
                }
                
                rename($fullTempPath, $fullNewPath);
                $data['receipt'] = $newPath;
            }
        }

        // Remove temp_receipt_path from data
        unset($data['temp_receipt_path']);

        $data['user_id'] = session('user_id');

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', __('messages.expense_added_success'));
    }

    // edit form
    public function edit(Expense $expense)
    {
        if ($expense->user_id != session('user_id')) {
            abort(403);
        }

        return view('expenses.edit', compact('expense'));
    }

    // update record
    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id != session('user_id')) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'category' => 'required|in:transportation,food,home_utilities,entertainment',
            'amount' => 'required|integer|min:0',
            'receipt' => 'nullable|file|max:10240',
            'expense_date' => 'required|date',
        ]);

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', __('messages.expense_updated_success'));
    }

    // delete
    public function destroy(Expense $expense)
    {
        if ($expense->user_id != session('user_id')) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', __('messages.expense_deleted_success'));
    }
}