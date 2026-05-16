@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Back Button -->
    <div class="mt-0">
        <a href="/home" 
           class="inline-flex items-center space-x-2 text-slate-600 hover:text-slate-900 font-medium group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>{{ __('messages.back_to_home') }}</span>
        </a>
    </div>

    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">
                    {{ __('messages.my_expenses') }}
                </h1>
                <p class="text-slate-600">{{ __('messages.manage_track_expenses') }}</p>
            </div>

            <a href="{{ route('expenses.create') }}" 
               class="group inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-blue-500/30 transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ __('messages.add_expense') }}</span>
            </a>
        </div>
    </div>

    <!-- Budget Overview Cards -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Total Budget Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">{{ __('messages.monthly') }}</span>
            </div>
            <h3 class="text-lg font-medium mb-2">{{ __('messages.total_budget') }}</h3>
            <p class="text-3xl font-bold mb-4">Rp {{ number_format($totalBudget, 0, ',', '.') }}</p>
            <div class="flex items-center justify-between text-sm">
                <span>{{ __('messages.spent') }}: Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                <span>{{ __('messages.remaining') }}: Rp {{ number_format($totalRemaining, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Monthly Progress Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">{{ __('messages.spending_progress') }}</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-slate-600 mb-2">
                    <span>{{ __('messages.total_spent') }}</span>
                    <span class="font-semibold">{{ $totalBudget > 0 ? number_format(($totalSpent / $totalBudget) * 100, 1) : 0 }}%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500" 
                         style="width: {{ $totalBudget > 0 ? min(($totalSpent / $totalBudget) * 100, 100) : 0 }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-500 mt-2">
                    <span>Rp 0</span>
                    <span>Rp {{ number_format($totalBudget, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Budget Cards -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ __('messages.category_budgets') }}</h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            @foreach(['transportation', 'food', 'home_utilities', 'entertainment'] as $cat)
            <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center
                            {{ $cat === 'transportation' ? 'bg-purple-100' : '' }}
                            {{ $cat === 'food' ? 'bg-green-100' : '' }}
                            {{ $cat === 'home_utilities' ? 'bg-orange-100' : '' }}
                            {{ $cat === 'entertainment' ? 'bg-pink-100' : '' }}">
                            @if($cat === 'transportation')
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            @elseif($cat === 'food')
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            @elseif($cat === 'home_utilities')
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ __('messages.category_' . $cat) }}</h3>
                            <p class="text-xs text-slate-500">
                                {{ $categoryPercentages[$cat] }}% {{ __('messages.of_budget') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold {{ $categorySpending[$cat]['percentage'] > 100 ? 'text-red-600' : 'text-slate-700' }}">
                        {{ number_format($categorySpending[$cat]['percentage'], 0) }}%
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="h-3 rounded-full transition-all duration-500
                            {{ $categorySpending[$cat]['percentage'] > 100 ? 'bg-red-500' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}" 
                             style="width: {{ min($categorySpending[$cat]['percentage'], 100) }}%"></div>
                    </div>
                </div>

                <!-- Budget Details -->
                <div class="flex justify-between text-sm">
                    <div>
                        <p class="text-slate-600">{{ __('messages.spent') }}</p>
                        <p class="font-semibold text-slate-900">Rp {{ number_format($categorySpending[$cat]['spent'], 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-600">{{ __('messages.budget') }}</p>
                        <p class="font-semibold text-slate-900">Rp {{ number_format($categorySpending[$cat]['budget'], 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($categorySpending[$cat]['remaining'] < 0)
                    <div class="mt-3 text-xs text-red-600 bg-red-50 px-3 py-2 rounded-lg">
                        ⚠️ {{ __('messages.over_budget') }}: Rp {{ number_format(abs($categorySpending[$cat]['remaining']), 0, ',', '.') }}
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">{{ __('messages.filter_expenses') }}</h3>
        <form method="GET" action="{{ route('expenses.index') }}" class="grid md:grid-cols-4 gap-4">
            <!-- Month -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.month') }}</label>
                <select name="month" class="w-full px-4 py-2.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ __('messages.month_' . $m) }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Year -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.year') }}</label>
                <select name="year" class="w-full px-4 py-2.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.category') }}</label>
                <select name="category" class="w-full px-4 py-2.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    <option value="all" {{ $category === 'all' ? 'selected' : '' }}>{{ __('messages.all_categories') }}</option>
                    <option value="transportation" {{ $category === 'transportation' ? 'selected' : '' }}>{{ __('messages.category_transportation') }}</option>
                    <option value="food" {{ $category === 'food' ? 'selected' : '' }}>{{ __('messages.category_food') }}</option>
                    <option value="home_utilities" {{ $category === 'home_utilities' ? 'selected' : '' }}>{{ __('messages.category_home_utilities') }}</option>
                    <option value="entertainment" {{ $category === 'entertainment' ? 'selected' : '' }}>{{ __('messages.category_entertainment') }}</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                    {{ __('messages.apply_filters') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Expenses List -->
    @if($expenses->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ __('messages.no_expenses_found') }}</h3>
            <p class="text-slate-600 mb-6">{{ __('messages.no_expenses_description') }}</p>
            <a href="{{ route('expenses.create') }}" 
               class="inline-flex items-center space-x-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ __('messages.add_first_expense') }}</span>
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($expenses as $expense)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden group">
                    
                    <!-- Card Header -->
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-6 border-b border-slate-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full mb-3
                                    {{ $expense->category === 'transportation' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $expense->category === 'food' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $expense->category === 'home_utilities' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $expense->category === 'entertainment' ? 'bg-pink-100 text-pink-700' : '' }}">
                                    {{ __('messages.category_' . $expense->category) }}
                                </span>
                                <h3 class="text-lg font-semibold text-slate-900 mb-1">{{ $expense->title }}</h3>
                                <p class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </p>
                                @if($expense->details)
                                    <p class="text-sm text-slate-600 mt-2">{{ Str::limit($expense->details, 60) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-6 space-y-4">
                        <!-- Date -->
                        <div class="flex items-center space-x-3 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $expense->expense_date ? $expense->expense_date->format('d M Y, H:i') : '-' }}</span>
                        </div>

                        <!-- Receipt Info -->
                        <div class="flex items-center space-x-3 text-sm">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            @if ($expense->receipt)
                                <a href="{{ asset('storage/' . $expense->receipt) }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-700 font-medium hover:underline">
                                    {{ __('messages.view') }}
                                </a>
                            @else
                                <span class="text-slate-400">{{ __('messages.no_file') }}</span>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('expenses.edit', $expense->id) }}" 
                               class="flex-1 flex items-center justify-center space-x-2 bg-slate-100 text-slate-700 px-4 py-2.5 rounded-xl font-medium hover:bg-slate-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>{{ __('messages.edit') }}</span>
                            </a>

                            <form action="{{ route('expenses.destroy', $expense->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center justify-center space-x-2 bg-red-50 text-red-600 px-4 py-2.5 rounded-xl font-medium hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>{{ __('messages.delete') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


</div>

@endsection