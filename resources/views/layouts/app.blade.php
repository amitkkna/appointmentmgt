<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Doctor Appointment System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-hospital-user me-2"></i> Doctor Appointment System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctors.index') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                                <i class="fas fa-user-md me-1"></i> {{ __('Doctors') }}
                            </a>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                                    <i class="fas fa-calendar-check me-1"></i> {{ __('Appointments') }}
                                </a>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                                        <i class="fas fa-user me-1"></i> {{ __('Patients') }}
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctors.search') ? 'active' : '' }}" href="{{ route('doctors.search') }}">
                                    <i class="fas fa-search me-1"></i> {{ __('Find a Doctor') }}
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i> {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(auth()->user()->role === 'admin')
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                                        </a>
                                    @elseif(auth()->user()->role === 'doctor')
                                        <a class="dropdown-item" href="{{ route('doctor.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                                        </a>
                                        @php
                                            $doctorId = App\Models\Doctor::where('user_id', auth()->id())->first()->id ?? null;
                                        @endphp
                                        @if($doctorId)
                                            <a class="dropdown-item" href="{{ route('doctors.show', $doctorId) }}">
                                                <i class="fas fa-id-card me-1"></i> {{ __('My Profile') }}
                                            </a>
                                        @endif
                                    @elseif(auth()->user()->role === 'patient')
                                        <a class="dropdown-item" href="{{ route('patient.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                                        </a>
                                        @php
                                            $patientId = App\Models\Patient::where('user_id', auth()->id())->first()->id ?? null;
                                        @endphp
                                        @if($patientId)
                                            <a class="dropdown-item" href="{{ route('patients.show', $patientId) }}">
                                                <i class="fas fa-id-card me-1"></i> {{ __('My Profile') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('patients.medical-history', $patientId) }}">
                                                <i class="fas fa-notes-medical me-1"></i> {{ __('Medical History') }}
                                            </a>
                                        @endif
                                    @endif

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4 mb-md-5">
                        <h5 class="footer-heading">Doctor Appointment System</h5>
                        <p class="mt-3">Your trusted healthcare appointment system. Find the right doctor and book appointments with ease.</p>
                        <div class="social-icons mt-4">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-5">
                        <h5 class="footer-heading">Quick Links</h5>
                        <ul class="list-unstyled footer-links mt-3">
                            <li><a href="{{ route('home') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i> Home</a></li>
                            <li><a href="{{ route('doctors.index') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i> Doctors</a></li>
                            <li><a href="{{ route('doctors.search') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i> Find a Doctor</a></li>
                            @auth
                                <li><a href="{{ route('appointments.index') }}" class="footer-link"><i class="fas fa-chevron-right me-2"></i> Appointments</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mb-md-5">
                        <h5 class="footer-heading">For Patients</h5>
                        <ul class="list-unstyled footer-links mt-3">
                            <li><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2"></i> How it Works</a></li>
                            <li><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2"></i> FAQs</a></li>
                            <li><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2"></i> Patient Reviews</a></li>
                            <li><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2"></i> Health Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mb-md-5">
                        <h5 class="footer-heading">Contact Us</h5>
                        <ul class="list-unstyled contact-info mt-3">
                            <li class="d-flex mb-3">
                                <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                                <span>123 Medical Center Drive<br>Healthcare City, HC 12345</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-envelope me-3 mt-1"></i>
                                <span>info@doctorapp.com</span>
                            </li>
                            <li class="d-flex">
                                <i class="fas fa-phone-alt me-3 mt-1"></i>
                                <span>(123) 456-7890</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="footer-divider">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; {{ date('Y') }} Doctor Appointment System. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0"><a href="#" class="footer-link">Privacy Policy</a> | <a href="#" class="footer-link">Terms of Service</a></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
