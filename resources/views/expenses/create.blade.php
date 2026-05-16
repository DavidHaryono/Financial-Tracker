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
            {{ __('messages.add_expense') }}
        </h1>
        <p class="text-slate-600 mt-2">{{ __('messages.fill_expense_details') }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ __('messages.new_expense') }}</h2>
                    <p class="text-blue-100 text-sm">{{ __('messages.add_transaction_budget') }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6" id="expense-form">
            @csrf

            <input type="hidden" name="temp_receipt_path" id="temp_receipt_path">

            <!-- Receipt Upload (Moved to top) -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    <span>{{ __('messages.receipt') }}</span>
                    <span class="text-slate-400 font-normal text-xs">({{ __('messages.optional') }})</span>
                </label>
                
                <!-- AI Badge -->
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-3 mb-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-gradient-to-r from-purple-600 to-blue-600 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-700">
                            <strong class="font-semibold text-purple-900">AI-Powered:</strong> Upload a receipt and we'll automatically extract the details!
                        </p>
                    </div>
                </div>

                <!-- Custom File Upload -->
                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 hover:border-blue-500 transition-colors bg-slate-50" id="upload-area">
                    <input 
                        type="file" 
                        name="receipt"
                        id="receipt"
                        accept="image/*,.pdf"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="handleReceiptUpload(this)"
                    >
                    <div class="text-center" id="upload-content">
                        <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-medium text-slate-700 mb-1">
                            <span class="text-blue-600">{{ __('messages.click_to_upload') }}</span> {{ __('messages.or_drag_drop') }}
                        </p>
                        <p class="text-xs text-slate-500">{{ __('messages.file_types') }}</p>
                        <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                    </div>

                    <!-- Processing Indicator -->
                    <div class="text-center hidden" id="processing-indicator">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-3"></div>
                        <p class="text-sm font-medium text-slate-700">🤖 AI is analyzing your receipt...</p>
                        <p class="text-xs text-slate-500 mt-1">This may take a few seconds</p>
                    </div>

                    <!-- Success Indicator -->
                    <div class="text-center hidden" id="success-indicator">
                        <div class="w-12 h-12 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-green-700">✨ Receipt analyzed successfully!</p>
                        <p class="text-xs text-slate-500 mt-1" id="uploaded-file-name"></p>
                        <button type="button" onclick="resetUpload()" class="text-xs text-blue-600 hover:text-blue-700 mt-2">
                            Upload different receipt
                        </button>
                    </div>
                </div>
                @error('receipt')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror

                <!-- Error Display -->
                <div id="parse-error" class="hidden bg-red-50 border border-red-200 rounded-lg p-3 mt-2">
                    <p class="text-sm text-red-700" id="parse-error-message"></p>
                </div>
            </div>

            <!-- Title Field -->
            <div class="space-y-2">
                <label class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>{{ __('messages.title') }}</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title"
                    required
                    value="{{ old('title') }}"
                    placeholder="{{ __('messages.title_placeholder') }}"
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                >
                @error('title')
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
                    id="category"
                    required
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                >
                    <option value="">{{ __('messages.select_category') }}</option>
                    <option value="transportation" {{ old('category') === 'transportation' ? 'selected' : '' }}>{{ __('messages.category_transportation') }}</option>
                    <option value="food" {{ old('category') === 'food' ? 'selected' : '' }}>{{ __('messages.category_food') }}</option>
                    <option value="home_utilities" {{ old('category') === 'home_utilities' ? 'selected' : '' }}>{{ __('messages.category_home_utilities') }}</option>
                    <option value="entertainment" {{ old('category') === 'entertainment' ? 'selected' : '' }}>{{ __('messages.category_entertainment') }}</option>
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
                    <span>{{ __('messages.amount') }}</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-medium">Rp</span>
                    </div>
                    <input 
                        type="number" 
                        name="amount" 
                        id="amount"
                        required
                        min="0"
                        value="{{ old('amount') }}"
                        placeholder="0"
                        class="w-full pl-14 pr-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                    >
                </div>
                @error('amount')
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
                    id="details"
                    rows="3"
                    placeholder="{{ __('messages.details_placeholder') }}"
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-none"
                >{{ old('details') }}</textarea>
                @error('details')
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
                    value="{{ old('expense_date', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3.5 border-2 border-slate-200 rounded-xl bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                >
                @error('expense_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button 
                    type="submit"
                    id="submit-btn"
                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-blue-500/30 transform hover:-translate-y-1 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ __('messages.save') }}</span>
                </button>

                <a href="{{ route('expenses.index') }}" 
                   class="px-8 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors flex items-center justify-center">
                    {{ __('messages.cancel') }}
                </a>
            </div>

        </form>
    </div>

    <!-- Help Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-6">
        <div class="flex items-start space-x-4">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 mb-1">💡 {{ __('messages.quick_tip') }}</h3>
                <p class="text-sm text-slate-600">Upload a receipt image and our AI will automatically fill in the title, category, and amount. You can then review and adjust before saving!</p>
            </div>
        </div>
    </div>

</div>

<script>
let currentFile = null;

async function handleReceiptUpload(input) {
    if (!input.files || !input.files[0]) return;

    currentFile = input.files[0];
    const fileName = currentFile.name;

    // Show processing state
    showProcessingState();

    // Create FormData and upload
    const formData = new FormData();
    formData.append('receipt', currentFile);
    formData.append('_token', '{{ csrf_token() }}');

    try {
        const response = await fetch('{{ route("expenses.parseReceipt") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const result = await response.json();

        if (result.success) {
            // Fill form fields with extracted data
            if (result.data.title) {
                document.getElementById('title').value = result.data.title;
            }
            if (result.data.category) {
                document.getElementById('category').value = result.data.category;
            }
            if (result.data.amount) {
                document.getElementById('amount').value = result.data.amount;
            }

            // Store temp path for later use
            if (result.temp_path) {
                document.getElementById('temp_receipt_path').value = result.temp_path;
            }

            // Show success state
            showSuccessState(fileName);

            // Hide error if it was showing
            document.getElementById('parse-error').classList.add('hidden');

        } else {
            // Show error
            showErrorState(result.message || 'Failed to parse receipt');
            showUploadState();
        }

    } catch (error) {
        console.error('Upload error:', error);
        showErrorState('Network error. Please try again.');
        showUploadState();
    }
}

function showProcessingState() {
    document.getElementById('upload-content').classList.add('hidden');
    document.getElementById('processing-indicator').classList.remove('hidden');
    document.getElementById('success-indicator').classList.add('hidden');
}

function showSuccessState(fileName) {
    document.getElementById('upload-content').classList.add('hidden');
    document.getElementById('processing-indicator').classList.add('hidden');
    document.getElementById('success-indicator').classList.remove('hidden');
    document.getElementById('uploaded-file-name').textContent = fileName;
}

function showUploadState() {
    document.getElementById('upload-content').classList.remove('hidden');
    document.getElementById('processing-indicator').classList.add('hidden');
    document.getElementById('success-indicator').classList.add('hidden');
}

function showErrorState(message) {
    const errorDiv = document.getElementById('parse-error');
    const errorMessage = document.getElementById('parse-error-message');
    errorMessage.textContent = message;
    errorDiv.classList.remove('hidden');
}

function resetUpload() {
    // Clear the file input
    document.getElementById('receipt').value = '';
    document.getElementById('temp_receipt_path').value = '';
    currentFile = null;

    // Reset to upload state
    showUploadState();

    // Clear form fields
    document.getElementById('title').value = '';
    document.getElementById('category').value = '';
    document.getElementById('amount').value = '';
}

// Handle form submission
document.getElementById('expense-form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Saving...</span>';
});
</script>

@endsection