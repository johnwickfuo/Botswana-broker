@extends('layouts.base')

@section('title', 'Frequently Asked Questions')

@inject('content', 'App\Http\Controllers\FrontController')
@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden bg-white border-b border-gray-200">
    <div class="relative z-10 px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider uppercase rounded-full bg-sky-50 text-primary">
                <i class="fas fa-file-contract mr-2"></i>Knowledge Base
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl md:text-5xl">
                <span class="block">Frequently Asked Questions</span>
                <span class="block mt-2 text-primary">Investing With {{ $settings->site_name }}</span>
            </h1>
            <p class="max-w-2xl mt-5 mx-auto text-xl text-gray-600">
                Answers to common questions about investing in verified national government assets of the United Arab Emirates.
            </p>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-12 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div x-data="{ activeCategory: 'investing' }" class="space-y-10">

            <!-- FAQ Category Tabs -->
            <div class="flex flex-wrap justify-center gap-2 md:gap-4">
                <button @click="activeCategory = 'investing'" :class="{'bg-primary text-white': activeCategory === 'investing', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'investing'}" class="px-4 py-2 rounded-lg transition-all duration-200 text-sm md:text-base font-medium focus:outline-none">
                    <i class="fas fa-coins mr-2"></i>Investing
                </button>
                <button @click="activeCategory = 'assets'" :class="{'bg-primary text-white': activeCategory === 'assets', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'assets'}" class="px-4 py-2 rounded-lg transition-all duration-200 text-sm md:text-base font-medium focus:outline-none">
                    <i class="fas fa-building-columns mr-2"></i>National Assets
                </button>
                <button @click="activeCategory = 'returns'" :class="{'bg-primary text-white': activeCategory === 'returns', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'returns'}" class="px-4 py-2 rounded-lg transition-all duration-200 text-sm md:text-base font-medium focus:outline-none">
                    <i class="fas fa-landmark mr-2"></i>Returns &amp; Maturity
                </button>
                <button @click="activeCategory = 'account'" :class="{'bg-primary text-white': activeCategory === 'account', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'account'}" class="px-4 py-2 rounded-lg transition-all duration-200 text-sm md:text-base font-medium focus:outline-none">
                    <i class="fas fa-shield-halved mr-2"></i>Account &amp; KYC
                </button>
            </div>

            <!-- Investing Category -->
            <div x-show="activeCategory === 'investing'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="divide-y divide-gray-200" x-data="{active: null}">
                        <div class="py-4">
                            <button @click="active !== 0 ? active = 0 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">How do I invest with {{ $settings->site_name }}?</h4>
                                <svg :class="{'rotate-180': active === 0}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 0" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Create an account and complete identity verification (KYC). Then browse the available investment plans, choose a plan and an amount within its limits, review and accept the terms, and confirm. Your funds are then committed to the selected plan until maturity.</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 1 ? active = 1 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">Is there a minimum or maximum amount I can invest?</h4>
                                <svg :class="{'rotate-180': active === 1}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 1" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Yes. Each plan sets its own minimum and maximum investment limits. The applicable limits are shown on the plan before you confirm, and your chosen amount must fall within those limits.</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 2 ? active = 2 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">Can I invest in more than one plan?</h4>
                                <svg :class="{'rotate-180': active === 2}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 2" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Yes. You may hold investments across more than one available plan at the same time, each governed by its own amount limits and maturity terms.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- National Assets Category -->
            <div x-show="activeCategory === 'assets'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="divide-y divide-gray-200" x-data="{active: null}">
                        <div class="py-4">
                            <button @click="active !== 0 ? active = 0 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">What are national assets?</h4>
                                <svg :class="{'rotate-180': active === 0}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 0" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>National assets are verified government-backed assets of the United Arab Emirates that have been reviewed and documented before being made available for investment on the platform.</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 1 ? active = 1 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">How do I know an asset is genuine?</h4>
                                <svg :class="{'rotate-180': active === 1}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 1" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Each asset is documented and its supporting certificates are published on the platform. This allows citizens to review what stands behind a plan before deciding to invest.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Returns & Maturity Category -->
            <div x-show="activeCategory === 'returns'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="divide-y divide-gray-200" x-data="{active: null}">
                        <div class="py-4">
                            <button @click="active !== 0 ? active = 0 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">How are returns paid?</h4>
                                <svg :class="{'rotate-180': active === 0}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 0" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Returns are paid periodically over the term of the plan according to the terms you accept when investing. The original principal is returned to you at maturity.</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 1 ? active = 1 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">In what currency are returns paid?</h4>
                                <svg :class="{'rotate-180': active === 1}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 1" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>All returns and the return of principal are paid in Pula (BWP).</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 2 ? active = 2 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">What is the maturity or lock period?</h4>
                                <svg :class="{'rotate-180': active === 2}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 2" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Each plan has a defined maturity, or lock period, during which your funds remain committed. The maturity term is shown on the plan before you confirm. Your principal is returned once the plan reaches maturity.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account & KYC Category -->
            <div x-show="activeCategory === 'account'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="divide-y divide-gray-200" x-data="{active: null}">
                        <div class="py-4">
                            <button @click="active !== 0 ? active = 0 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">Is identity verification (KYC) required?</h4>
                                <svg :class="{'rotate-180': active === 0}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 0" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Yes. Identity verification (KYC) is required before you can invest. It protects your account and helps ensure that investments are held securely by the rightful citizen.</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 1 ? active = 1 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">Who can open an account?</h4>
                                <svg :class="{'rotate-180': active === 1}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 1" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Eligible citizens who can complete identity verification may open an account and invest. You will be asked to provide the information needed to verify your identity during registration.</p>
                            </div>
                        </div>

                        <div class="py-4">
                            <button @click="active !== 2 ? active = 2 : active = null" class="flex justify-between items-center w-full focus:outline-none">
                                <h4 class="text-lg font-medium text-gray-900 text-left">What if I forget my password?</h4>
                                <svg :class="{'rotate-180': active === 2}" class="w-5 h-5 text-primary transform transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === 2" x-collapse x-cloak class="mt-3 text-gray-600">
                                <p>Use the password reset link on the login page. Enter your registered email address and follow the instructions to securely reset your {{ $settings->site_name }} account password.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden p-8">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-handshake text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Still have questions?</h2>
                </div>
                <p class="text-gray-600 mb-6">Our support team can help you with any questions about investing in national assets of the Republic of Botswana.</p>
                <div class="flex space-x-4 pt-2">
                    <a href="/contact" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-primary hover:opacity-90 transition duration-150">
                        Contact Support
                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden p-8">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-landmark text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Ready to invest?</h2>
                </div>
                <p class="text-gray-600 mb-6">Browse verified national government assets and choose a plan that suits you. Getting started takes only a few minutes.</p>
                <div class="flex space-x-4 pt-2">
                    <a href="{{ route('invest.index') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-primary hover:opacity-90 transition duration-150">
                        Explore Investment Plans
                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Back to top button -->
<button id="back-to-top" class="fixed bottom-8 right-8 z-50 bg-primary hover:opacity-90 text-white rounded-full p-3 shadow-lg transition-all duration-300 opacity-0 translate-y-10" aria-label="Back to top">
    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'translate-y-10');
                backToTopButton.classList.add('opacity-100', 'translate-y-0');
            } else {
                backToTopButton.classList.add('opacity-0', 'translate-y-10');
                backToTopButton.classList.remove('opacity-100', 'translate-y-0');
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
@endsection
