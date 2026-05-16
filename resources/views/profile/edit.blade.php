@extends('layouts.app')
@section('title', __('messages.edit_profile'))

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('home') }}" 
           class="inline-flex items-center space-x-2 text-slate-600 hover:text-slate-900 font-medium group mb-4">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>{{ __('messages.back_to_home') }}</span>
        </a>
        
        <h1 class="text-4xl font-bold text-slate-900">
            {{ __('messages.edit_profile') }}
        </h1>
        <p class="text-slate-600 mt-2">{{ __('messages.manage_account_info') }}</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        
        <!-- Sidebar -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                <!-- User Avatar -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl font-bold text-white">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-600">{{ $user->email }}</p>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-3 pt-6 border-t border-slate-200 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.monthly_income') }}</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($user->income, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.savings_rate') }}</span>
                        <span class="font-semibold text-green-600">{{ $user->savings_percentage }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.spendable') }}</span>
                        <span class="font-semibold text-blue-600">Rp {{ number_format($user->getTotalBudget(), 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-3 pt-6 border-t border-slate-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.category_transportation') }}</span>
                        <span class="font-semibold text-green-600">{{ $user->budget_transportation }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.category_food') }}</span>
                        <span class="font-semibold text-green-600">{{ $user->budget_food }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.category_home_utilities') }}</span>
                        <span class="font-semibold text-green-600">{{ $user->budget_home_utilities }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">{{ __('messages.category_entertainment') }}</span>
                        <span class="font-semibold text-green-600">{{ $user->budget_entertainment }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Personal Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ __('messages.personal_information') }}</h2>
                            <p class="text-blue-100 text-sm">{{ __('messages.update_account_details') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.update.personal') }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ __('messages.name') }}</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                        >
                        @error('name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <span>{{ __('messages.email') }}</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                        >
                        @error('email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3.5 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-blue-500/30 transform hover:-translate-y-1">
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Financial Settings Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ __('messages.financial_settings') }}</h2>
                            <p class="text-green-100 text-sm">{{ __('messages.configure_budget_savings') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.update.financial') }}" method="POST" class="p-8 space-y-6" id="financial-form">
                    @csrf

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('messages.monthly_income') }}</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-medium">Rp</span>
                            </div>
                            <input type="number" name="income" id="income-input" value="{{ old('income', $user->income) }}" required min="0" placeholder="0" class="w-full pl-14 pr-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                        @error('income')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>{{ __('messages.savings_percentage') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="savings_percentage" id="savings-input" value="{{ old('savings_percentage', $user->savings_percentage) }}" required min="0" max="100" class="w-full px-4 pr-12 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-medium">%</span>
                            </div>
                        </div>
                        @error('savings_percentage')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-900 mb-3 text-sm">{{ __('messages.budget_preview') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">{{ __('messages.monthly_income') }}:</span>
                                <span class="font-semibold text-slate-900" id="preview-income">Rp {{ number_format($user->income, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">{{ __('messages.savings') }} (<span id="preview-savings-percent">{{ $user->savings_percentage }}</span>%):</span>
                                <span class="font-semibold text-green-600" id="preview-savings">Rp {{ number_format($user->income * $user->savings_percentage / 100, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-green-300">
                                <span class="text-slate-900 font-semibold">{{ __('messages.spendable_budget') }}:</span>
                                <span class="font-bold text-blue-600" id="preview-budget">Rp {{ number_format($user->getTotalBudget(), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3.5 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-green-500/30 transform hover:-translate-y-1">
                            {{ __('messages.update_financial_settings') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Budget Category Distribution Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ __('messages.category_budgets') }}</h2>
                            <p class="text-orange-100 text-sm">Customize your spending allocation</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.update.categories') }}" method="POST" class="p-8 space-y-6" id="category-form">
                    @csrf
                   
                    <!-- Error Display -->
                    @error('budget_total')
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                        </div>
                    @enderror

                    <!-- Info Message -->
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-slate-700 mb-2">
                                    Adjust the percentage allocation for each spending category. <strong>Total must equal 100%</strong>.
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-600">Current Total:</span>
                                    <span class="text-sm font-bold" id="total-percentage">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Sliders -->
                    <div class="space-y-6">
                        @php
                            $categories = [
                                ['name' => 'transportation', 'color' => 'purple', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                                ['name' => 'food', 'color' => 'green', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                                ['name' => 'home_utilities', 'color' => 'orange', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['name' => 'entertainment', 'color' => 'pink', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z']
                            ];
                        @endphp

                        @foreach($categories as $cat)
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                                        <div class="w-8 h-8 bg-{{ $cat['color'] }}-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-{{ $cat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat['icon'] }}"></path>
                                            </svg>
                                        </div>
                                        <span>{{ __('messages.category_' . $cat['name']) }}</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input 
                                            type="number" 
                                            name="budget_{{ $cat['name'] }}" 
                                            id="cat-{{ $cat['name'] }}"
                                            value="{{ old('budget_' . $cat['name'], $user->{'budget_' . $cat['name']} ?? ['transportation' => 20, 'food' => 35, 'home_utilities' => 30, 'entertainment' => 15][$cat['name']]) }}"
                                            min="0"
                                            max="100"
                                            class="category-input w-16 px-2 py-1 text-center border-2 border-slate-200 rounded-lg text-sm font-semibold focus:border-{{ $cat['color'] }}-500 focus:ring-2 focus:ring-{{ $cat['color'] }}-500/10 outline-none"
                                        >
                                        <span class="text-sm font-medium text-slate-600">%</span>
                                    </div>
                                </div>
                                <input 
                                    type="range" 
                                    id="slider-{{ $cat['name'] }}"
                                    min="0" 
                                    max="100" 
                                    value="{{ old('budget_' . $cat['name'], $user->{'budget_' . $cat['name']} ?? ['transportation' => 20, 'food' => 35, 'home_utilities' => 30, 'entertainment' => 15][$cat['name']]) }}"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-{{ $cat['color'] }}-600"
                                >
                                <p class="text-xs text-slate-500" id="amount-{{ $cat['name'] }}">Rp {{ number_format($user->getTotalBudget() * ((old('budget_' . $cat['name'], $user->{'budget_' . $cat['name']} ?? ['transportation' => 20, 'food' => 35, 'home_utilities' => 30, 'entertainment' => 15][$cat['name']])) / 100), 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-orange-700 text-white px-8 py-3.5 rounded-xl font-semibold hover:from-orange-700 hover:to-orange-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-orange-500/30 transform hover:-translate-y-1">
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ __('messages.change_password') }}</h2>
                            <p class="text-purple-100 text-sm">{{ __('messages.update_account_security') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.update.password') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>{{ __('messages.current_password') }}</span>
                        </label>
                        <input type="password" name="current_password" placeholder="••••••••" class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        @error('current_password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            <span>{{ __('messages.new_password') }}</span>
                        </label>
                        <input type="password" name="new_password" placeholder="••••••••" class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        @error('new_password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>{{ __('messages.confirm_new_password') }}</span>
                        </label>
                        <input type="password" name="new_password_confirmation" placeholder="••••••••" class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-slate-700">
                                <p class="font-semibold mb-1">{{ __('messages.password_requirements') }}:</p>
                                <ul class="list-disc list-inside space-y-1 text-slate-600">
                                    <li>{{ __('messages.password_min_length') }}</li>
                                    <li>{{ __('messages.password_must_match') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-3.5 rounded-xl font-semibold hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-purple-500/30 transform hover:-translate-y-1">
                            {{ __('messages.update_password') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
// Live budget preview calculation for Financial Settings
document.addEventListener('DOMContentLoaded', function() {
    const incomeInput = document.getElementById('income-input');
    const savingsInput = document.getElementById('savings-input');
    
    if (incomeInput && savingsInput) {
        function updateFinancialPreview() {
            const income = parseInt(incomeInput.value) || 0;
            const savingsPercent = parseInt(savingsInput.value) || 0;
            const savings = income * savingsPercent / 100;
            const budget = income - savings;
            
            document.getElementById('preview-income').textContent = 'Rp ' + income.toLocaleString('id-ID');
            document.getElementById('preview-savings-percent').textContent = savingsPercent;
            document.getElementById('preview-savings').textContent = 'Rp ' + savings.toLocaleString('id-ID');
            document.getElementById('preview-budget').textContent = 'Rp ' + budget.toLocaleString('id-ID');
        }
        
        incomeInput.addEventListener('input', updateFinancialPreview);
        savingsInput.addEventListener('input', updateFinancialPreview);
    }

    // Category budget sliders synchronization
    const categories = ['transportation', 'food', 'home_utilities', 'entertainment'];
    const totalBudget = {{ $user->getTotalBudget() }};

    categories.forEach(function(category) {
        const input = document.getElementById('cat-' + category);
        const slider = document.getElementById('slider-' + category);
        const amountDisplay = document.getElementById('amount-' + category);

        if (input && slider) {
            // Sync input with slider
            input.addEventListener('input', function() {
                slider.value = this.value;
                updateCategoryAmount(category, this.value);
                updateTotal();
            });

            slider.addEventListener('input', function() {
                input.value = this.value;
                updateCategoryAmount(category, this.value);
                updateTotal();
            });
        }
    });

    function updateCategoryAmount(category, percentage) {
        const amount = totalBudget * (percentage / 100);
        const amountDisplay = document.getElementById('amount-' + category);
        if (amountDisplay) {
            amountDisplay.textContent = 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }
    }

    function updateTotal() {
        let total = 0;
        categories.forEach(function(category) {
            const input = document.getElementById('cat-' + category);
            if (input) {
                total += parseInt(input.value) || 0;
            }
        });

        const totalDisplay = document.getElementById('total-percentage');
        if (totalDisplay) {
            totalDisplay.textContent = total + '%';
            
            // Change color based on validity
            if (total === 100) {
                totalDisplay.classList.remove('text-red-600', 'text-amber-600');
                totalDisplay.classList.add('text-green-600');
            } else if (total > 90 && total < 110) {
                totalDisplay.classList.remove('text-red-600', 'text-green-600');
                totalDisplay.classList.add('text-amber-600');
            } else {
                totalDisplay.classList.remove('text-green-600', 'text-amber-600');
                totalDisplay.classList.add('text-red-600');
            }
        }
    }

    // Initial calculation
    updateTotal();
});
</script>

@endsection