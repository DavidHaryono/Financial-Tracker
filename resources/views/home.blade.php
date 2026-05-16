@extends('layouts.app')
@section('title', __('messages.dashboard'))

@section('content')

<div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1 sm:mb-2">
                    {{ __('messages.welcome', ['name' => session('user_name')]) }}
                </h1>
                <p class="text-sm sm:text-base text-blue-100">{{ __('messages.dashboard_subtitle') }}</p>
            </div>
            <div class="hidden sm:block">
                <div class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Row: Monthly Overview & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Monthly Overview Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 sm:mb-6">{{ __('messages.monthly_overview') }}</h2>
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 sm:gap-8">
                    
                    <!-- Big Percentage Circle -->
                    <div class="flex-1">
                        <div class="relative inline-flex items-center justify-center w-48 h-48 sm:w-56 sm:h-56 md:w-64 md:h-64 mx-auto">
                            <svg class="w-48 h-48 sm:w-56 sm:h-56 md:w-64 md:h-64 transform -rotate-90">
                                <circle cx="50%" cy="50%" r="44%" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-200" />
                                <circle cx="50%" cy="50%" r="44%" stroke="currentColor" stroke-width="12" fill="transparent" 
                                        stroke-dasharray="{{ 2 * 3.14159 * 112 }}" 
                                        stroke-dashoffset="{{ 2 * 3.14159 * 112 * (1 - min($totalSpent / max($totalBudget, 1), 1)) }}"
                                        class="{{ $totalSpent > $totalBudget ? 'text-red-500' : 'text-blue-500' }}" 
                                        stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900">{{ $totalBudget > 0 ? number_format(min(($totalSpent / $totalBudget) * 100, 100), 0) : 0 }}%</span>
                                <span class="text-sm sm:text-base md:text-lg text-slate-500">{{ __('messages.spent') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Metrics -->
                    <div class="flex-1 space-y-4 sm:space-y-6">
                        <!-- Total Spent -->
                        <div class="bg-blue-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-blue-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-base sm:text-lg font-semibold text-blue-900">{{ __('messages.total_spent') }}</h3>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                        </div>
                        
                        <!-- Remaining -->
                        <div class="bg-emerald-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-emerald-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-base sm:text-lg font-semibold text-emerald-900">{{ __('messages.remaining') }}</h3>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl sm:text-3xl font-bold {{ $remainingBudget >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                Rp {{ number_format(abs($remainingBudget), 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <!-- Savings -->
                        <div class="bg-purple-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-purple-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-base sm:text-lg font-semibold text-purple-900">{{ __('messages.savings') }}</h3>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <p class="text-2xl sm:text-3xl font-bold text-purple-600">Rp {{ number_format($savings, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-slate-200">
                    <h3 class="font-semibold text-slate-900 text-base sm:text-lg mb-4 sm:mb-6">{{ __('messages.category_breakdown') }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        @foreach(['transportation', 'food', 'home_utilities', 'entertainment'] as $cat)
                            @php
                                $catData = $categorySpending[$cat];
                                $percentage = $catData['budget'] > 0 ? ($catData['spent'] / $catData['budget']) * 100 : 0;
                                $isOverBudget = $catData['spent'] > $catData['budget'];
                            @endphp
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full
                                            {{ $cat === 'transportation' ? 'bg-purple-500' : '' }}
                                            {{ $cat === 'food' ? 'bg-green-500' : '' }}
                                            {{ $cat === 'home_utilities' ? 'bg-orange-500' : '' }}
                                            {{ $cat === 'entertainment' ? 'bg-pink-500' : '' }}">
                                        </div>
                                        <span class="font-medium text-slate-700 text-xs sm:text-sm">{{ __('messages.category_' . $cat) }}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 sm:h-3">
                                    <div class="h-2.5 sm:h-3 rounded-full transition-all duration-500
                                        {{ $isOverBudget ? 'bg-red-500' : '' }}
                                        {{ !$isOverBudget && $cat === 'transportation' ? 'bg-purple-500' : '' }}
                                        {{ !$isOverBudget && $cat === 'food' ? 'bg-green-500' : '' }}
                                        {{ !$isOverBudget && $cat === 'home_utilities' ? 'bg-orange-500' : '' }}
                                        {{ !$isOverBudget && $cat === 'entertainment' ? 'bg-pink-500' : '' }}" 
                                         style="width: {{ min($percentage, 100) }}%">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs sm:text-sm text-slate-600">
                                    <span>Rp {{ number_format($catData['spent'], 0, ',', '.') }}</span>
                                    <span class="text-slate-500">/ Rp {{ number_format($catData['budget'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 h-full">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-3 sm:mb-4">{{ __('messages.quick_actions') }}</h2>
                
                <div class="space-y-2 sm:space-y-3">
                    <a href="{{ route('expenses.create') }}" 
                       class="flex items-center space-x-3 p-3 sm:p-4 bg-blue-50 hover:bg-blue-100 rounded-lg sm:rounded-xl transition-colors group">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm sm:text-base">{{ __('messages.add_expense') }}</h3>
                            <p class="text-xs text-slate-600 truncate">{{ __('messages.add_expense_desc') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('expenses.index') }}" 
                       class="flex items-center space-x-3 p-3 sm:p-4 bg-slate-50 hover:bg-slate-100 rounded-lg sm:rounded-xl transition-colors group">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-slate-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm sm:text-base">{{ __('messages.view_all_expenses') }}</h3>
                            <p class="text-xs text-slate-600 truncate">{{ __('messages.view_all_expenses_desc') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center space-x-3 p-3 sm:p-4 bg-purple-50 hover:bg-purple-100 rounded-lg sm:rounded-xl transition-colors group">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm sm:text-base">{{ __('messages.edit_profile') }}</h3>
                            <p class="text-xs text-slate-600 truncate">{{ __('messages.edit_profile_desc') }}</p>
                        </div>
                    </a>
                </div>

                <!-- Recent Expenses -->
                <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ __('messages.recent_expenses') }}</h2>
                        <a href="{{ route('expenses.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                            {{ __('messages.view_all') }}
                        </a>
                    </div>

                    @if($recentExpenses->isEmpty())
                        <div class="text-center py-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600">{{ __('messages.no_recent_expenses') }}</p>
                        </div>
                    @else
                        <div class="space-y-2 sm:space-y-3">
                            @foreach($recentExpenses as $expense)
                                <div class="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 hover:bg-slate-50 rounded-lg transition-colors">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                        {{ $expense->category === 'transportation' ? 'bg-purple-100' : '' }}
                                        {{ $expense->category === 'food' ? 'bg-green-100' : '' }}
                                        {{ $expense->category === 'home_utilities' ? 'bg-orange-100' : '' }}
                                        {{ $expense->category === 'entertainment' ? 'bg-pink-100' : '' }}">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5
                                            {{ $expense->category === 'transportation' ? 'text-purple-600' : '' }}
                                            {{ $expense->category === 'food' ? 'text-green-600' : '' }}
                                            {{ $expense->category === 'home_utilities' ? 'text-orange-600' : '' }}
                                            {{ $expense->category === 'entertainment' ? 'text-pink-600' : '' }}" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($expense->category === 'transportation')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            @elseif($expense->category === 'food')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            @elseif($expense->category === 'home_utilities')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs sm:text-sm font-medium text-slate-900 truncate">{{ $expense->title }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ $expense->expense_date ? $expense->expense_date->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xs sm:text-sm font-semibold text-slate-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Spending Chart Full Width -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 md:p-8">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900">{{ __('messages.spending_trend') }}</h2>
        </div>
        
        <div class="h-[300px] sm:h-[350px] md:h-[400px]">
            <canvas id="spendingChart"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('spendingChart').getContext('2d');
    
    const chartData = @json($chartData);
    
    const allValues = [
        ...chartData.transportation,
        ...chartData.food,
        ...chartData.home_utilities,
        ...chartData.entertainment
    ];
    const maxValue = Math.max(...allValues);
    
    let suggestedMax;
    if (maxValue <= 100000) {
        suggestedMax = Math.ceil(maxValue / 10000) * 10000;
    } else if (maxValue <= 1000000) {
        suggestedMax = Math.ceil(maxValue / 100000) * 100000;
    } else {
        suggestedMax = Math.ceil(maxValue / 1000000) * 1000000;
    }
    
    // Check if mobile
    const isMobile = window.innerWidth < 640;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Transportation',
                    data: chartData.transportation,
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: isMobile ? 3 : 4,
                    pointHoverRadius: isMobile ? 5 : 6
                },
                {
                    label: 'Food',
                    data: chartData.food,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: isMobile ? 3 : 4,
                    pointHoverRadius: isMobile ? 5 : 6
                },
                {
                    label: 'Home & Utilities',
                    data: chartData.home_utilities,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: isMobile ? 3 : 4,
                    pointHoverRadius: isMobile ? 5 : 6
                },
                {
                    label: 'Entertainment',
                    data: chartData.entertainment,
                    borderColor: 'rgb(236, 72, 153)',
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: isMobile ? 3 : 4,
                    pointHoverRadius: isMobile ? 5 : 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: isMobile ? 12 : 20,
                        font: {
                            size: isMobile ? 10 : 12
                        },
                        boxWidth: isMobile ? 10 : 12,
                        boxHeight: isMobile ? 10 : 12
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: isMobile ? 8 : 12,
                    titleFont: {
                        size: isMobile ? 10 : 12
                    },
                    bodyFont: {
                        size: isMobile ? 10 : 12
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: suggestedMax || 1000000,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                            } else {
                                return 'Rp ' + value;
                            }
                        },
                        font: {
                            size: isMobile ? 9 : 11
                        },
                        padding: isMobile ? 5 : 10
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 9 : 11
                        },
                        padding: isMobile ? 5 : 10,
                        maxRotation: isMobile ? 45 : 0,
                        minRotation: isMobile ? 45 : 0
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});
</script>

@endsection