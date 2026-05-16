<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'income',
        'savings_percentage',
        'budget_transportation',
        'budget_food',
        'budget_home_utilities',
        'budget_entertainment',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'income' => 'integer',
            'savings_percentage' => 'integer',
            'budget_transportation' => 'integer',
            'budget_food' => 'integer',
            'budget_home_utilities' => 'integer',
            'budget_entertainment' => 'integer',
        ];
    }

    /**
     * Get total budget (income - savings)
     */
    public function getTotalBudget()
    {
        return $this->income * (100 - $this->savings_percentage) / 100;
    }

    /**
     * Get budget by category using custom percentages
     */
    public function getCategoryBudget($category)
    {
        $totalBudget = $this->getTotalBudget();
        
        // Use custom budget percentages from database
        $percentages = [
            'transportation' => $this->budget_transportation ?? 20,
            'food' => $this->budget_food ?? 35,
            'home_utilities' => $this->budget_home_utilities ?? 30,
            'entertainment' => $this->budget_entertainment ?? 15,
        ];

        $percentage = $percentages[$category] ?? 0;
        
        return $totalBudget * ($percentage / 100);
    }

    /**
     * Get all category budgets
     */
    public function getAllCategoryBudgets()
    {
        return [
            'transportation' => $this->getCategoryBudget('transportation'),
            'food' => $this->getCategoryBudget('food'),
            'home_utilities' => $this->getCategoryBudget('home_utilities'),
            'entertainment' => $this->getCategoryBudget('entertainment'),
        ];
    }

    /**
     * Get category budget percentages
     */
    public function getCategoryPercentages()
    {
        return [
            'transportation' => $this->budget_transportation ?? 20,
            'food' => $this->budget_food ?? 35,
            'home_utilities' => $this->budget_home_utilities ?? 30,
            'entertainment' => $this->budget_entertainment ?? 15,
        ];
    }
}