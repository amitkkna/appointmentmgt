<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Doctor Appointment System</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Doctor Appointment System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('doctors.index') }}">{{ __('Doctors') }}</a>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index') }}">{{ __('Appointments') }}</a>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('patients.index') }}">{{ __('Patients') }}</a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('doctors.search') }}">{{ __('Find a Doctor') }}</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ auth()->user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(auth()->user()->role === 'admin')
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            {{ __('Dashboard') }}
                                        </a>
                                    @elseif(auth()->user()->role === 'doctor')
                                        <a class="dropdown-item" href="{{ route('doctor.dashboard') }}">
                                            {{ __('Dashboard') }}
                                        </a>
                                        @php
                                            $doctorId = App\Models\Doctor::where('user_id', auth()->id())->first()->id ?? null;
                                        @endphp
                                        @if($doctorId)
                                            <a class="dropdown-item" href="{{ route('doctors.show', $doctorId) }}">
                                                {{ __('My Profile') }}
                                            </a>
                                        @endif
                                    @elseif(auth()->user()->role === 'patient')
                                        <a class="dropdown-item" href="{{ route('patient.dashboard') }}">
                                            {{ __('Dashboard') }}
                                        </a>
                                        @php
                                            $patientId = App\Models\Patient::where('user_id', auth()->id())->first()->id ?? null;
                                        @endphp
                                        @if($patientId)
                                            <a class="dropdown-item" href="{{ route('patients.show', $patientId) }}">
                                                {{ __('My Profile') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('patients.medical-history', $patientId) }}">
                                                {{ __('Medical History') }}
                                            </a>
                                        @endif
                                    @endif

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <h5 class="footer-heading">{{ config('app.name', 'Laravel') }}</h5>
                        <p>Your trusted healthcare appointment system. Find the right doctor and book appointments with ease.</p>
                    </div>
                    <div class="col-md-2 mb-4">
                        <h5 class="footer-heading">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                            <li><a href="{{ route('doctors.index') }}" class="footer-link">Doctors</a></li>
                            <li><a href="{{ route('doctors.search') }}" class="footer-link">Find a Doctor</a></li>
                            @auth
                                <li><a href="{{ route('appointments.index') }}" class="footer-link">Appointments</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h5 class="footer-heading">For Patients</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="footer-link">How it Works</a></li>
                            <li><a href="#" class="footer-link">FAQs</a></li>
                            <li><a href="#" class="footer-link">Patient Reviews</a></li>
                            <li><a href="#" class="footer-link">Health Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h5 class="footer-heading">Contact Us</h5>
                        <address>
                            <p>123 Medical Center Drive<br>Healthcare City, HC 12345</p>
                            <p>Email: info@doctorapp.com<br>Phone: (123) 456-7890</p>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
