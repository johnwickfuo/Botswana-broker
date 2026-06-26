@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h3 class="fw-bold mb-3">About {{ $settings->site_name }}</h3>
                    <p class="text-muted">The official investment platform of the United Arab Emirates, open to investors worldwide</p>
                </div>

                <x-danger-alert />
                <x-success-alert />

                <!-- Hero Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body text-center text-white py-5">
                                <div class="mb-4">
                                    <i class="fas fa-landmark fa-4x mb-3 opacity-75"></i>
                                </div>
                                <h1 class="display-4 fw-bold mb-3">{{ $settings->site_name }}</h1>
                                <p class="lead mb-4">The official investment platform of the United Arab Emirates, open to investors worldwide</p>
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <p class="mb-4">We are committed to advancing economic development by offering investors worldwide a secure, transparent, and well-regulated platform for investment and wealth growth.</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                    <a href="#" target="_blank" class="btn btn-light btn-lg px-4">
                                        <i class="fas fa-headset me-2"></i>Contact Support
                                    </a>
                                    <a href="#" target="_blank" class="btn btn-outline-light btn-lg px-4">
                                        <i class="fas fa-globe me-2"></i>Visit Website
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="row g-4">
                    <!-- Regulated Investment -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 hover-shadow-lg transition-all">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                                        <i class="fas fa-landmark text-primary fa-2x"></i>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold text-dark">Regulated Investment</h5>
                                <p class="card-text text-muted small">A government-backed platform operating under national oversight</p>
                                <div class="mt-auto">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">Officially Sanctioned</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Secure Funding -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 hover-shadow-lg transition-all">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                                        <i class="fas fa-coins text-success fa-2x"></i>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold text-dark">Secure Funding</h5>
                                <p class="card-text text-muted small">Safe deposit and withdrawal of funds with full transparency</p>
                                <div class="mt-auto">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">Protected Funds</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Citizen Support -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 hover-shadow-lg transition-all">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                                        <i class="fas fa-life-ring text-warning fa-2x"></i>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold text-dark">Dedicated Support</h5>
                                <p class="card-text text-muted small">Assistance for every investor throughout their journey</p>
                                <div class="mt-auto">
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compliance -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 hover-shadow-lg transition-all">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                                        <i class="fas fa-file-contract text-info fa-2x"></i>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold text-dark">Compliance & Trust</h5>
                                <p class="card-text text-muted small">Operating to the highest standards of integrity and accountability</p>
                                <div class="mt-auto">
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">Transparent</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Sections -->
                <div class="row mt-5">
                    <!-- Our Mission -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white border-0">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-landmark me-2"></i>Our National Mission
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">{{ $settings->site_name }} exists to broaden access to safe, well-regulated investment opportunities for investors worldwide, supporting economic growth and individual financial independence.</p>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-dark">Investment Opportunities:</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-chart-line text-warning me-2"></i>Diversified Investment Plans</li>
                                            <li class="mb-2"><i class="fas fa-university text-primary me-2"></i>Secure Funding & Settlement</li>
                                            <li class="mb-2"><i class="fas fa-coins text-success me-2"></i>Multiple Asset Classes</li>
                                            <li class="mb-2"><i class="fas fa-file-contract text-info me-2"></i>Transparent Reporting</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge bg-success">✅ Government Oversight</span>
                                    <span class="badge bg-success">✅ Secure & Compliant</span>
                                    <span class="badge bg-success">✅ Accessible to All Citizens</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Onboarding -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-success text-white border-0">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-user-check me-2 text-white"></i>Getting Started
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Opening an account is straightforward and secure. Our team guides every investor through registration, verification, and their first deposit.</p>

                                <div class="mb-3">
                                    <h6 class="fw-bold text-dark">What's Included:</h6>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Simple & Secure Registration</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Identity Verification</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Guided First Deposit</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Protected Account Access</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 mb-0">
                                    <i class="fas fa-info-circle me-2 text white"></i>
                                    <strong>Straightforward:</strong> Begin investing with confidence and clear guidance!
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Investor Support -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-warning text-dark border-0">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-shield-alt me-2 text-white"></i>Investor Support & Protection
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">We provide ongoing support and safeguards for every investor, ensuring funds and accounts remain secure, transparent, and well governed.</p>

                                <div class="mb-3">
                                    <h6 class="fw-bold text-dark">Support Includes:</h6>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-headset text-primary me-2"></i>Dedicated Assistance</li>
                                                <li class="mb-2"><i class="fas fa-lock text-danger me-2"></i>Account & Fund Protection</li>
                                                <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i>Security & Compliance Measures</li>
                                                <li class="mb-2"><i class="fas fa-comments text-info me-2"></i>Guidance & Consultation</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 fs-6">Secure & Trusted</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transparency -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-info text-white border-0">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-file-contract me-2"></i>Transparency & Governance
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">As the official investment platform of the United Arab Emirates, open to investors worldwide, we uphold the highest standards of transparency, accountability, and public trust.</p>

                                <div class="mb-3">
                                    <h6 class="fw-bold text-dark">Our Commitments:</h6>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-balance-scale text-primary me-2"></i>Regulatory Compliance</li>
                                                <li class="mb-2"><i class="fas fa-eye text-success me-2"></i>Clear & Open Reporting</li>
                                                <li class="mb-2"><i class="fas fa-users text-warning me-2"></i>Public Accountability</li>
                                                <li class="mb-2"><i class="fas fa-handshake text-info me-2"></i>Trusted Stewardship</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success border-0 mb-0">
                                    <i class="fas fa-bullseye me-2"></i>
                                    <strong>Goal:</strong> Serve investors worldwide with integrity and trust!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
                            <div class="card-body text-center text-white py-5">
                                <h3 class="fw-bold mb-4">Ready to Get Started?</h3>
                                <p class="lead mb-4">Open your investment account with the United Arab Emirates today</p>

                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="p-3 bg-white rounded">
                                                    <i class="fas fa-headset fa-2x mb-2 text-dark"></i>
                                                    <h6 class="fw-bold text-dark">Investor Support</h6>
                                                    <a href="#" target="_blank" class="btn btn-primary btn-sm">Contact Us</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-white rounded">
                                                    <i class="fas fa-globe fa-2x mb-2 text-dark"></i>
                                                    <h6 class="fw-bold text-dark">Visit Website</h6>
                                                    <a href="#" target="_blank" class="btn btn-primary btn-sm">{{ $settings->site_name }}</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-white rounded">
                                                    <i class="fas fa-phone fa-2x mb-2 text-dark"></i>
                                                    <h6 class="fw-bold text-dark">Phone Support</h6>
                                                    <a href="#" class="btn btn-primary btn-sm">Call Us</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow-lg:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        .card-header {
            border-bottom: none !important;
        }

        .badge {
            font-size: 0.75rem;
        }

        .alert {
            border-radius: 0.5rem;
        }
    </style>
@endsection
