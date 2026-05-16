<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'details',
        'category',
        'amount',
        'receipt',
        'expense_date',
    ];

    protected $casts = [
        'expense_date' => 'datetime',
        'amount' => 'integer',
    ];

    /**
     * Get the user that owns the expense
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by month and year
     */
    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereMonth('expense_date', $month)
                     ->whereYear('expense_date', $year);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }
}