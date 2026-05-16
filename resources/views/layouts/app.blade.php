<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Budget App')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      font-family: 'Inter', sans-serif;
    }
    
    /* Smooth transitions */
    * {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    
    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }
    
    /* Fade in animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .fade-in {
      animation: fadeIn 0.5s ease-out;
    }
    
    /* Alert slide out animation */
    @keyframes slideOut {
      from {
        opacity: 1;
        transform: translateX(0);
      }
      to {
        opacity: 0;
        transform: translateX(100%);
      }
    }
    
    .alert-dismissing {
      animation: slideOut 0.5s ease-out forwards;
    }

    /* Mobile menu animation */
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .mobile-menu-enter {
      animation: slideDown 0.2s ease-out;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 min-h-screen">

  <!-- Modern Navigation -->
  <nav class="bg-white/80 backdrop-blur-lg border-b border-slate-200/60 sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
      <div class="flex justify-between items-center">
        
        <!-- Logo -->
        @if(session('user_name'))
          <a href="{{ route('home') }}" class="group flex items-center space-x-2">
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center transform group-hover:scale-105">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <span class="text-lg sm:text-xl font-semibold text-slate-800 group-hover:text-blue-600 hidden sm:inline">
              {{ __('messages.app_name') }}
            </span>
          </a>
        @else
          <a href="{{ route('welcome') }}" class="group flex items-center space-x-2">
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center transform group-hover:scale-105">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <span class="text-lg sm:text-xl font-semibold text-slate-800 group-hover:text-blue-600 hidden sm:inline">
              {{ __('messages.app_name') }}
            </span>
          </a>
        @endif

        <div class="flex items-center space-x-2 sm:space-x-6">
          
          <!-- Language Switcher -->
          <div class="flex items-center space-x-1 sm:space-x-2 bg-slate-100 rounded-full p-0.5 sm:p-1">
            <a href="/locale/en" 
               class="px-2 py-1 sm:px-4 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium {{ session('locale','en') == 'en' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
              EN
            </a>
            <a href="/locale/id" 
               class="px-2 py-1 sm:px-4 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium {{ session('locale') == 'id' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
              ID
            </a>
          </div>

          <!-- Authentication - Desktop -->
          @if(session('user_name'))
            <div class="hidden md:flex items-center space-x-4">
              <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-medium">
                    {{ substr(session('user_name'), 0, 1) }}
                  </span>
                </div>
                <span class="text-sm font-medium text-slate-700 max-w-[120px] truncate">{{ session('user_name') }}</span>
              </a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg">
                  {{ __('messages.logout') }}
                </button>
              </form>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2 hover:bg-slate-100 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          @else
            <a href="{{ route('login') }}" 
               class="px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transform hover:-translate-y-0.5">
              {{ __('messages.login') }}
            </a>
          @endif
        </div>
      </div>

      <!-- Mobile Menu -->
      @if(session('user_name'))
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-2 border-t border-slate-200 pt-4 mobile-menu-enter">
          <div class="space-y-2">
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-3 hover:bg-slate-100 rounded-lg transition-colors">
              <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-medium">
                  {{ substr(session('user_name'), 0, 1) }}
                </span>
              </div>
              <div class="flex-1">
                <p class="font-medium text-slate-900">{{ session('user_name') }}</p>
                <p class="text-xs text-slate-500">{{ __('messages.edit_profile') }}</p>
              </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
              @csrf
              <button class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                {{ __('messages.logout') }}
              </button>
            </form>
          </div>
        </div>
      @endif
    </div>
  </nav>

  <!-- Alert Messages -->
  <div class="container mx-auto px-4 sm:px-6 mt-4 sm:mt-6 fade-in">
    @if(session('success'))
      <div id="success-alert" class="bg-green-50 border border-green-200 text-green-800 px-4 sm:px-6 py-3 sm:py-4 rounded-lg sm:rounded-xl shadow-sm flex items-center space-x-2 sm:space-x-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium flex-1 text-sm sm:text-base">{{ session('success') }}</span>
        <button onclick="dismissAlert('success-alert')" class="text-green-600 hover:text-green-800 flex-shrink-0">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div id="error-alert" class="bg-red-50 border border-red-200 text-red-800 px-4 sm:px-6 py-3 sm:py-4 rounded-lg sm:rounded-xl shadow-sm flex items-center space-x-2 sm:space-x-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium flex-1 text-sm sm:text-base">{{ session('error') }}</span>
        <button onclick="dismissAlert('error-alert')" class="text-red-600 hover:text-red-800 flex-shrink-0">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @endif
  </div>

  <script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuBtn = document.getElementById('mobile-menu-btn');
      const mobileMenu = document.getElementById('mobile-menu');
      
      if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
          mobileMenu.classList.toggle('hidden');
        });
      }
    });

    // Auto-dismiss alerts after 3 seconds
    function autoDismissAlerts() {
      const successAlert = document.getElementById('success-alert');
      const errorAlert = document.getElementById('error-alert');
      
      if (successAlert) {
        setTimeout(() => dismissAlert('success-alert'), 3000);
      }
      
      if (errorAlert) {
        setTimeout(() => dismissAlert('error-alert'), 3000);
      }
    }

    function dismissAlert(alertId) {
      const alert = document.getElementById(alertId);
      if (alert) {
        alert.classList.add('alert-dismissing');
        setTimeout(() => alert.remove(), 500);
      }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', autoDismissAlerts);
  </script>

  <!-- Main Content -->
  <main class="mx-auto px-4 sm:px-6 py-4 sm:py-6 md:py-8 fade-in">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="py-8 sm:py-12 px-4 sm:px-6 bg-slate-900 text-white mt-8">
      <div class="container mx-auto max-w-6xl">
          <div class="text-center">
              <div class="flex items-center justify-center space-x-2 mb-3 sm:mb-4">
                  <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center">
                      <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                  </div>
                  <span class="text-xl sm:text-2xl font-bold">{{ __('messages.app_name') }}</span>
              </div>
              <p class="text-slate-400 mb-4 sm:mb-6 text-sm sm:text-base">{{ __('messages.footer_tagline') }}</p>
              <p class="text-slate-500 text-xs sm:text-sm">{{ __('messages.footer_copyright') }}</p>
          </div>
      </div>
  </footer>

</body>
</html>