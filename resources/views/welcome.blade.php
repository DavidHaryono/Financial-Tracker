@extends('layouts.app')
@section('title', __('messages.app_name'))

@section('content')
<!-- Hero Section -->
<section class="py-6 px-6">
    <div class="container mx-auto max-w-6xl">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            
            <!-- Left Content -->
            <div class="space-y-8">
                <div class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                    ✨ {{ __('messages.welcome_badge') }}
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-bold text-slate-900 leading-tight">
                    {{ __('messages.hero_title_part1') }}
                    <span class="bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent">
                        {{ __('messages.hero_title_part2') }}
                    </span>
                </h1>
                
                <p class="text-xl text-slate-600 leading-relaxed">
                    {{ __('messages.hero_description') }}
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-500/30 transform hover:-translate-y-1 flex items-center space-x-2">
                        <span>{{ __('messages.start_tracking') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    
                    <a href="#features"
                       class="px-8 py-4 bg-white text-slate-700 font-semibold rounded-xl hover:shadow-lg border border-slate-200 hover:border-slate-300 transform hover:-translate-y-1">
                        {{ __('messages.learn_more') }}
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="flex flex-wrap gap-8 pt-8 border-t border-slate-200">
                    <div>
                        <div class="text-3xl font-bold text-slate-900">10K+</div>
                        <div class="text-sm text-slate-600">{{ __('messages.stat_active_users') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-slate-900">$2M+</div>
                        <div class="text-sm text-slate-600">{{ __('messages.stat_expenses_tracked') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-slate-900">4.9★</div>
                        <div class="text-sm text-slate-600">{{ __('messages.stat_user_rating') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Visual -->
            <div class="relative">
                <div class="relative">
                    <!-- Main Card -->
                    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-slate-200">
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">{{ __('messages.monthly_overview') }}</h3>
                                <div class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                    +12.5%
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ __('messages.category_shopping') }}</div>
                                            <div class="text-xs text-slate-500">12 {{ __('messages.transactions') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-slate-900">$450</div>
                                        <div class="text-xs text-slate-500">30%</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ __('messages.category_food') }}</div>
                                            <div class="text-xs text-slate-500">24 {{ __('messages.transactions') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-slate-900">$320</div>
                                        <div class="text-xs text-slate-500">21%</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ __('messages.category_housing') }}</div>
                                            <div class="text-xs text-slate-500">3 {{ __('messages.transactions') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-slate-900">$1,200</div>
                                        <div class="text-xs text-slate-500">49%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -top-4 -right-4 bg-gradient-to-br from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl shadow-lg">
                        <div class="text-xs font-medium">{{ __('messages.total_saved') }}</div>
                        <div class="text-lg font-bold">$1,250</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 px-6 bg-white">
    <div class="container mx-auto max-w-6xl">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                🚀 {{ __('messages.powerful_features') }}
            </div>
            <h2 class="text-4xl font-bold text-slate-900 mb-4">{{ __('messages.features_title') }}</h2>
            <p class="text-xl text-slate-600">{{ __('messages.features_subtitle') }}</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.feature1_title') }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.feature1_description') }}</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.feature2_title') }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.feature2_description') }}</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.feature3_title') }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.feature3_description') }}</p>
            </div>
            
            <!-- Feature 4 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.feature4_title') }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.feature4_description') }}</p>
            </div>
            
            <!-- Feature 5 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.feature5_title') }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.feature5_description') }}</p>
            </div>
            
            <!-- Feature 6 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.feature6_title') }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.feature6_description') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 px-6 bg-gradient-to-br from-blue-50 to-slate-50">
    <div class="container mx-auto max-w-6xl">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                📋 {{ __('messages.simple_process') }}
            </div>
            <h2 class="text-4xl font-bold text-slate-900 mb-4">{{ __('messages.how_it_works_title') }}</h2>
            <p class="text-xl text-slate-600">{{ __('messages.how_it_works_subtitle') }}</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="relative">
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-lg">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mb-6">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.step1_title') }}</h3>
                    <p class="text-slate-600">{{ __('messages.step1_description') }}</p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-0.5 bg-blue-300"></div>
            </div>
            
            <!-- Step 2 -->
            <div class="relative">
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-lg">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mb-6">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.step2_title') }}</h3>
                    <p class="text-slate-600">{{ __('messages.step2_description') }}</p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-0.5 bg-blue-300"></div>
            </div>
            
            <!-- Step 3 -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-lg">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mb-6">
                    3
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ __('messages.step3_title') }}</h3>
                <p class="text-slate-600">{{ __('messages.step3_description') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 px-6">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl p-12 text-center text-white shadow-2xl">
            <h2 class="text-4xl font-bold mb-4">{{ __('messages.cta_title') }}</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                {{ __('messages.cta_description') }}
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}"
                   class="px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 shadow-lg transform hover:-translate-y-1 transition-all">
                    {{ __('messages.cta_start_free') }}
                </a>
                <a href="{{ route('login') }}"
                   class="px-8 py-4 bg-blue-800 text-white font-semibold rounded-xl hover:bg-blue-900 transform hover:-translate-y-1 transition-all">
                    {{ __('messages.cta_sign_in') }}
                </a>
            </div>
            <p class="text-sm text-blue-200 mt-6">{{ __('messages.cta_note') }}</p>
        </div>
    </div>
</section>
@endsection