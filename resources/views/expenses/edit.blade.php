@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('expenses.index') }}" 
           class="inline-flex items-center space-x-2 text-slate-600 hover:text-slate-900 font-medium group mb-4">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>{{ __('messages.back_to_expenses') }}</span>
        </a>
        
        <h1 class="text-4xl font-bold text-slate-900">
            {{ __('messages.edit_expense_title') }}
        </h1>
        <p class="text-slate-600 mt-2">{{ __('messages.update_expense_details') }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ __('messages.edit_expense') }}</h2>
                    <p class="text-blue-100 text-sm">{{ __('messages.modify_expense_info') }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <!-- Title Field -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>{{ __('messages.expense_title') }}</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    value="{{ old('title', $expense->title) }}" 
                    required
                    placeholder="{{ __('messages.title_placeholder') }}"
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                >
                @error('title')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Details Field -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span>{{ __('messages.details') }}</span>
                    <span class="text-slate-400 font-normal text-xs">({{ __('messages.optional') }})</span>
                </label>
                <textarea 
                    name="details" 
                    rows="3"
                    placeholder="{{ __('messages.details_placeholder') }}"
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-none"
                >{{ old('details', $expense->details) }}</textarea>
                @error('details')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Field -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>{{ __('messages.category') }}</span>
                </label>
                <select 
                    name="category" 
                    required
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                >
                    <option value="">{{ __('messages.select_category') }}</option>
                    <option value="transportation" {{ old('category', $expense->category) === 'transportation' ? 'selected' : '' }}>{{ __('messages.category_transportation') }}</option>
                    <option value="food" {{ old('category', $expense->category) === 'food' ? 'selected' : '' }}>{{ __('messages.category_food') }}</option>
                    <option value="home_utilities" {{ old('category', $expense->category) === 'home_utilities' ? 'selected' : '' }}>{{ __('messages.category_home_utilities') }}</option>
                    <option value="entertainment" {{ old('category', $expense->category) === 'entertainment' ? 'selected' : '' }}>{{ __('messages.category_entertainment') }}</option>
                </select>
                @error('category')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount Field -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('messages.expense_amount') }}</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-medium">Rp</span>
                    </div>
                    <input 
                        type="number" 
                        name="amount" 
                        value="{{ old('amount', $expense->amount) }}" 
                        required
                        min="0"
                        placeholder="0"
                        class="w-full pl-14 pr-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                    >
                </div>
                @error('amount')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expense Date Field -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ __('messages.expense_date') }}</span>
                </label>
                <input 
                    type="datetime-local" 
                    name="expense_date" 
                    required
                    value="{{ old('expense_date', $expense->expense_date ? $expense->expense_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                >
                @error('expense_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Receipt Display -->
            @if ($expense->receipt)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ __('messages.current_receipt') }}</p>
                            <p class="text-xs text-slate-600">{{ __('messages.click_view_receipt') }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $expense->receipt) }}" 
                       target="_blank" 
                       class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <span>{{ __('messages.view_receipt') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endif

            <!-- Receipt Upload -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    <span>{{ __('messages.expense_receipt_optional') }}</span>
                </label>
                
                <!-- Custom File Upload -->
                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 hover:border-blue-500 transition-colors bg-slate-50">
                    <input 
                        type="file" 
                        name="receipt"
                        id="receipt"
                        accept="image/*,.pdf"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="updateFileName(this)"
                    >
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-medium text-slate-700 mb-1">
                            <span class="text-blue-600">{{ __('messages.click_to_upload') }}</span> {{ __('messages.or_drag_drop') }}
                        </p>
                        <p class="text-xs text-slate-500">{{ __('messages.file_types') }}</p>
                        <p class="text-xs text-slate-500 mt-2">{{ __('messages.upload_new_replace') }}</p>
                        <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                    </div>
                </div>
                @error('receipt')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button 
                    type="submit"
                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-blue-500/30 transform hover:-translate-y-1 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ __('messages.update') }}</span>
                </button>

                <a href="{{ route('expenses.index') }}" 
                   class="px-8 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center justify-center">
                    {{ __('messages.cancel') }}
                </a>
            </div>

        </form>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-6">
        <div class="flex items-start space-x-4">
            <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 mb-1">⚠️ {{ __('messages.note') }}</h3>
                <p class="text-sm text-slate-600">{{ __('messages.changes_saved_immediately') }}</p>
            </div>
        </div>
    </div>

</div>

<script>
function updateFileName(input) {
    const fileName = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileName.textContent = '📎 ' + input.files[0].name;
        fileName.classList.remove('hidden');
    }
}
</script>

@endsection