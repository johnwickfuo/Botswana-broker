
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#00A3DD',
                        secondary: '#10B981',
                        accent: '#00A3DD',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        dark: {
                            100: '#374151',
                            200: '#1F2937',
                            300: '#111827',
                            400: '#0F172A',
                            500: '#0B1120',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        /* Alpine.js x-cloak directive */
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar for dark mode */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1F2937;
        }
        ::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6B7280;
        }

        /* Form elements styling */
        .form-input {
            @apply bg-dark-100 border-0 rounded-lg px-4 py-3 text-gray-100 font-medium focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-200;
        }

        .form-select {
            @apply bg-dark-100 border-0 rounded-lg px-4 py-3 text-gray-100 font-medium focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-200;
        }

        .btn-primary {
            @apply bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200;
        }

        .btn-secondary {
            @apply bg-dark-100 hover:bg-dark-200 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200;
        }

        /* Navbar and mobile menu animations */
        .navbar-dropdown {
            @apply transition-all duration-300 ease-in-out transform origin-top;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* Light mode overrides */
        html:not(.dark) {
            @apply bg-gray-100 text-gray-900;
        }

        html:not(.dark) body {
            @apply bg-gray-100 text-gray-900;
        }

        html:not(.dark) .bg-dark-400 {
            @apply bg-white;
        }

        html:not(.dark) .bg-dark-300 {
            @apply bg-gray-50;
        }

        html:not(.dark) .border-gray-800 {
            @apply border-gray-200;
        }

        html:not(.dark) .text-gray-200,
        html:not(.dark) .text-gray-300,
        html:not(.dark) .text-gray-400 {
            @apply text-gray-700;
        }

        html:not(.dark) .hover\:bg-dark-200:hover,
        html:not(.dark) .hover\:bg-gray-700:hover {
            @apply hover:bg-gray-200;
        }

        html:not(.dark) .bg-dark-500 {
            @apply bg-gray-100;
        }
    </style>

    <title>{{$settings->site_name}} | Republic of Botswana Investment Platform</title>
    <link rel="manifest" href="./">
    <meta name="theme-color" content="#111111">    <meta property="x-session-id" content="ghJjEOrjZ3KUPun1UQksVUbvK88y21dgIhKtb8GT">
    <meta property="og:site_name" content="{{$settings->site_name}}">
    <meta property="og:description" content="Official investment platform of the Republic of Botswana. Invest in verified national government assets with transparent, regulated returns.">
    <meta name="description" content="Official investment platform of the Republic of Botswana. Invest in verified national government assets with transparent, regulated returns.">
    <meta name="keywords" content="Republic of Botswana, government investment, national assets, Pula, treasury, public investment, sovereign assets, Botswana investment platform">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Republic of Botswana Investment Platform | {{$settings->site_name}}">
    <meta property="og:image" content="{{ asset('storage/app/public/'.$settings->logo) }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/app/public/'.$settings->favicon)}}" type="image/x-icon">

    <!-- Preload key assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery (required for some components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript utilities -->
    <script>
        // Utility function for mobile menu
        document.addEventListener('alpine:init', () => {
            Alpine.store('navigation', {
                open: false,
                toggle() {
                    this.open = !this.open;
                },
                close() {
                    this.open = false;
                }
            });

            // Theme toggler
            Alpine.store('darkMode', {
                on: true,
                toggle() {
                    this.on = !this.on;
                    if (this.on) {
                        document.documentElement.classList.add('dark');
                        document.body.classList.add('bg-gray-900');
                        document.body.classList.remove('bg-gray-100');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.body.classList.remove('bg-gray-900');
                        document.body.classList.add('bg-gray-100');
                    }
                    localStorage.setItem('darkMode', this.on ? 'dark' : 'light');
                }
            });
        });

        // Initialize theme from local storage
        document.addEventListener('DOMContentLoaded', () => {
            const theme = localStorage.getItem('darkMode');
            if (theme === 'light') {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('bg-gray-900');
                document.body.classList.add('bg-gray-100');
                if (window.Alpine) {
                    Alpine.store('darkMode').on = false;
                }
            }
        });
    </script>
    
  
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '0f196f16e1dc85aaf2f8e09cc9dd2c5566322d61';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>


    <link rel="stylesheet" href="{{ asset('css/botswana-brand.css') }}">
</head>

<body class="antialiased text-gray-200 bg-gray-900 font-sans min-h-screen flex flex-col">
    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:bg-blue-700 focus:text-white focus:fixed focus:px-4 focus:py-2 focus:top-2 focus:left-2 focus:z-50">
        Skip to main content
    </a>




















    <!-- Header -->
<header class="bg-gray-900 border-b border-gray-800 relative z-50">
    <!-- Top Navigation Bar -->
    <div x-data="{ mobileMenuOpen: false }" class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative">
            <div class="flex items-center justify-between h-16">
                <!-- Logo + national emblem slot (client-uploaded; never fabricated) -->
                <div class="flex-shrink-0">
                    <a href="/" class="flex items-center gap-3">
                        @if (!empty($settings->emblem))
                            <img class="h-12 w-auto" src="{{ asset('storage/app/public/'.$settings->emblem) }}" alt="Republic of Botswana emblem">
                            <span class="hidden sm:block h-10 w-px bg-black" aria-hidden="true"></span>
                        @endif
                        <img class="h-16 w-auto" src="{{ asset('storage/app/public/'.$settings->logo)}}" alt="{{$settings->site_name}}">
                    </a>
                </div>

                <!-- Main Navigation - Desktop -->
                <nav class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('invest.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-200 hover:text-white">Investment Plans</a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="group inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-200 hover:text-white focus:outline-none">
                            <span>Company</span>
                            <svg class="ml-2 h-4 w-4 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-dark-300 ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                            <a href="about" class="block px-4 py-2 text-sm text-gray-200 hover:bg-dark-200">About Us</a>
                            <a href="why-us" class="block px-4 py-2 text-sm text-gray-200 hover:bg-dark-200">Why Us</a>
                            <a href="faq" class="block px-4 py-2 text-sm text-gray-200 hover:bg-dark-200">FAQ</a>
                            <a href="regulation" class="block px-4 py-2 text-sm text-gray-200 hover:bg-dark-200">Legal & Regulation</a>
                        </div>
                    </div>

                    <a href="for-traders" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-200 hover:text-white">Investor Guide</a>
                    <a href="contacts" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-200 hover:text-white">Contact</a>
                </nav>

                <!-- Right Navigation -->
                <div class="hidden md:flex items-center">
                    <div class="flex space-x-1 mr-4">
                        <a href="#" class="text-gray-400 hover:text-gray-200 p-1" aria-label="Desktop Version">
                            <i class="fas fa-desktop"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-200 p-1" aria-label="Windows App">
                            <i class="fab fa-windows"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-200 p-1" aria-label="Android App">
                            <i class="fab fa-android"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-200 p-1" aria-label="iOS App">
                            <i class="fab fa-apple"></i>
                        </a>
                    </div>

                    <!-- Language Selector (Desktop) -->
                 

                    <!-- Dark/Light Mode Toggle -->
                    <!--<button-->
                    <!--    x-data-->
                    <!--    @click="$store.darkMode.toggle()"-->
                    <!--    class="p-2 text-gray-400 hover:text-gray-200 mr-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-600"-->
                    <!--    aria-label="Toggle dark/light mode"-->
                    <!-->-->
                    <!--    <i x-show="$store.darkMode.on" class="fas fa-sun"></i>-->
                    <!--    <i x-show="!$store.darkMode.on" class="fas fa-moon"></i>-->
                    <!--</button>-->

                    <div class="flex items-center space-x-4">
                        <a href="login" class="text-gray-200 hover:text-white flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            <span>Log in</span>
                        </a>
                        <a href="register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Sign up
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button and theme toggle -->
                <div class="flex md:hidden items-center space-x-2">
                    <!-- Mobile Theme Toggle -->
                    <!--<button-->
                    <!--    x-data-->
                    <!--    @click="$store.darkMode.toggle()"-->
                    <!--    class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"-->
                    <!--    aria-label="Toggle dark/light mode"-->
                    <!-->-->
                    <!--    <i x-show="$store.darkMode.on" class="fas fa-sun text-lg"></i>-->
                    <!--    <i x-show="!$store.darkMode.on" class="fas fa-moon text-lg"></i>-->
                    <!--</button>-->

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" class="md:hidden" id="mobile-menu" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <!-- Mobile Navigation -->
                <a href="{{ route('invest.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">Investment Plans</a>

                <div x-data="{ open: false }" class="py-1">
                    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                        <span>Company</span>
                        <svg class="h-4 w-4 text-gray-400" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4">
                        <a href="about" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">About Us</a>
                        <a href="why-us" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">Why Us</a>
                        <a href="faq" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">FAQ</a>
                        <a href="regulation" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">Legal & Regulation</a>
                    </div>
                </div>

                <a href="for-traders" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">Education</a>
                <a href="contacts" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">Contact</a>

                <!-- Language Selector (Mobile) -->
               
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <div class="flex items-center justify-between px-4">
                        <div class="flex items-center space-x-3">
                            <a href="login" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-lock mr-1"></i> Log in
                            </a>
                            <a href="register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Sign up
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>



    <!--  sub menu -->



<!-- Main Content Area -->
<main id="main-content" class="flex-grow">
    @yield('content')
</main>







<!-- Footer -->
<footer class="bg-dark-400 text-gray-300">
    <!-- Upper Footer -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <div class="flex items-center mb-6">
                            <a href="/" class="flex items-center">
                                <img class="h-8 w-auto" src="{{ asset('storage/app/public/'.$settings->logo)}}" alt="{{$settings->site_name}}">
                            </a>
                        </div>
                        <p class="text-sm text-gray-400 mb-2 font-semibold text-white">Republic of Botswana</p>
                        <p class="text-sm text-gray-400 mb-6">
                            {{$settings->site_name}} is the official investment platform of the Republic of Botswana, enabling citizens to invest in verified national government assets with transparent, regulated returns.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white">
                                <span class="sr-only">Twitter</span>
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <span class="sr-only">LinkedIn</span>
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="mailto:{{$settings->contact_email}}" class="text-gray-400 hover:text-white">
                                <span class="sr-only">Email</span>
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('invest.index') }}" class="text-sm text-gray-400 hover:text-white transition">Investment Plans</a></li>
                            <li><a href="about" class="text-sm text-gray-400 hover:text-white transition">About the Platform</a></li>
                            <li><a href="contacts" class="text-sm text-gray-400 hover:text-white transition">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Legal & Disclosure -->
                    <div>
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Legal &amp; Disclosure</h3>
                        <ul class="space-y-3">
                            <li><a href="terms" class="text-sm text-gray-400 hover:text-white transition">Terms of Use</a></li>
                            <li><a href="privacy" class="text-sm text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                            <li><a href="licensing" class="text-sm text-gray-400 hover:text-white transition">Licensing &amp; Disclosure</a></li>
                            <li><a href="faq" class="text-sm text-gray-400 hover:text-white transition">FAQ</a></li>
                        </ul>
                    </div>

                    <!-- Account -->
                    <div>
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Your Account</h3>
                        <ul class="space-y-3">
                            <li><a href="login" class="text-sm text-gray-400 hover:text-white transition">Log In</a></li>
                            <li><a href="register" class="text-sm text-gray-400 hover:text-white transition">Create Account</a></li>
                            <li><a href="{{ route('investments.mine') }}" class="text-sm text-gray-400 hover:text-white transition">My Portfolio</a></li>
                            <li><a href="contact" class="text-sm text-gray-400 hover:text-white transition">Help Centre</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Availability -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h4 class="text-sm font-semibold text-white">Available On</h4>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="#" class="flex items-center text-gray-400 hover:text-white" aria-label="Web Platform">
                            <i class="fas fa-desktop mr-2"></i>
                            <span class="text-sm">Web</span>
                        </a>
                        <a href="#" class="flex items-center text-gray-400 hover:text-white" aria-label="Windows App">
                            <i class="fab fa-windows mr-2"></i>
                            <span class="text-sm">Windows</span>
                        </a>
                        <a href="#" class="flex items-center text-gray-400 hover:text-white" aria-label="Android App">
                            <i class="fab fa-android mr-2"></i>
                            <span class="text-sm">Android</span>
                        </a>
                        <a href="#" class="flex items-center text-gray-400 hover:text-white" aria-label="iOS App">
                            <i class="fab fa-apple mr-2"></i>
                            <span class="text-sm">iOS</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer & Legal -->
    <div class="bg-dark-500 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="text-xs text-gray-400">
                    <p class="mb-4 leading-relaxed">
                        <span class="font-semibold text-gray-300">DISCLOSURE:</span> {{$settings->site_name}} is the official investment platform of the Republic of Botswana. All investments are in verified national government assets and are subject to the published terms, offer windows and maturity conditions. The value of an investment and the returns realised depend on the terms of the selected plan. Please read the plan terms and disclosure documents carefully before investing.
                    </p>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <a href="terms" class="text-blue-400 hover:text-blue-300 transition">Terms of Use</a>
                        <a href="privacy" class="text-blue-400 hover:text-blue-300 transition">Privacy Policy</a>
                        <a href="licensing" class="text-blue-400 hover:text-blue-300 transition">Legal &amp; Disclosure</a>
                    </div>
                    <p>© <script>document.write(new Date().getFullYear())</script> {{$settings->site_name}} · Republic of Botswana. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
    
    @include('layouts.lang')
</footer>

<!-- Market ticker removed in the Republic of Botswana rebrand -->

<!-- Live Chat Button -->
{{-- <div class="fixed bottom-20 right-6 z-40">
    <button onclick="openLiveChat(event)" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
        <i class="fas fa-comments text-xl"></i>
    </button>
</div> --}}











        <!-- TradingView ticker removed in the Republic of Botswana rebrand -->


        <!-- GetButton.io widget -->
<!--<script type="text/javascript">-->
<!--    (function () {-->
<!--        var options = {-->
            <!--whatsapp: "{{$settings->whatsapp}}", // WhatsApp number-->
            <!--call_to_action: "Message us", // Call to action-->
            <!--button_color: "#FF6550", // Color of button-->
            <!--position: "right", // Position may be 'right' or 'left'-->
<!--        };-->
<!--        var proto = 'https:', host = "getbutton.io", url = proto + '//static.' + host;-->
<!--        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';-->
<!--        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };-->
<!--        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);-->
<!--    })();-->
<!--</script>-->
<!-- Additional Scripts -->
<script>
    // Live Chat Function
    function openLiveChat(e) {
        e.preventDefault();
        console.log('Opening live chat...');
        // Implement your live chat functionality here
    }

    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any custom components
        console.log('DOM loaded, initializing components...');
    });

    // Analytics (preserved from original)
    function bindGAEvents(eventCategory, callback, iter) {
        if (typeof iter === "undefined") { iter = 1; }
        if(window.dataLayer) {
            if (typeof callback === "function") {
                callback();
            }
        } else if(iter > 2 && typeof callback === "function") {
            callback();
        } else {
            setTimeout(function(){
                bindGAEvents(eventCategory, callback, iter + 1);
            }, 500);
        }
    }

    $(document).ready(function() {
        if('' == '1') {
            bindGAEvents('ex_new_user');
        }

        $(document).on('click', '.start-trading, .open-live-account, .open-demo-account', function (event) {
            var redirectLink = $(this).attr('href');
            bindGAEvents('ex_button', function(){
                window.location.href = redirectLink;
            });
            event.preventDefault();
        });
    });
</script>

@include('layouts.livechat')
</body>
</html>
