@extends('layouts.app')

@section('content')

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4">Your Health, Our Priority</h1>
            <p class="lead">Book appointments with the best doctors in your area with just a few clicks.</p>
            <div class="hero-buttons">
                <a href="{{ route('doctors.search') }}" class="btn btn-light btn-lg">Find a Doctor</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg ms-md-3 mt-3 mt-md-0">Register Now</a>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">We provide a wide range of medical services to meet your healthcare needs</p>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
                        <h5 class="card-title">Easy Appointment Booking</h5>
                        <p class="card-text">Book appointments with your preferred doctors at your convenient time with our simple booking system.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="card-icon"><i class="fas fa-user-md"></i></div>
                        <h5 class="card-title">Expert Doctors</h5>
                        <p class="card-text">Access to a wide range of specialized doctors with years of experience in various medical fields.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="card-icon"><i class="fas fa-bell"></i></div>
                        <h5 class="card-title">Appointment Reminders</h5>
                        <p class="card-text">Get timely reminders for your upcoming appointments so you never miss an important consultation.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="card-icon"><i class="fas fa-notes-medical"></i></div>
                        <h5 class="card-title">Medical History</h5>
                        <p class="card-text">Keep track of your medical history and share it with your doctors for better healthcare management.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="card-icon"><i class="fas fa-star"></i></div>
                        <h5 class="card-title">Doctor Reviews</h5>
                        <p class="card-text">Read reviews from other patients and share your own experience to help others make informed decisions.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="card-icon"><i class="fas fa-credit-card"></i></div>
                        <h5 class="card-title">Online Payment</h5>
                        <p class="card-text">Secure online payment for your appointments and consultations with multiple payment options.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="cta-section py-5 my-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h2 class="cta-title">Ready to book your appointment?</h2>
                    <p class="cta-text">Join thousands of patients who have found the right doctors for their healthcare needs.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('doctors.search') }}" class="btn btn-primary btn-lg">Find a Doctor Now</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Simple steps to book your appointment</p>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="step-card text-center">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fas fa-search"></i></div>
                    <h5 class="step-title">Find a Doctor</h5>
                    <p class="step-text">Search for doctors by specialty, location, or availability</p>
                </div>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="step-card text-center">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h5 class="step-title">Book Appointment</h5>
                    <p class="step-text">Select a convenient date and time for your appointment</p>
                </div>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="step-card text-center">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fas fa-credit-card"></i></div>
                    <h5 class="step-title">Make Payment</h5>
                    <p class="step-text">Securely pay for your appointment online</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">4</div>
                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                    <h5 class="step-title">Get Confirmation</h5>
                    <p class="step-text">Receive confirmation and reminders for your appointment</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .section-header {
        margin-bottom: 3rem;
    }

    .section-title {
        font-weight: 700;
        margin-bottom: 1rem;
        color: #212529;
        position: relative;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: #4cc9f0;
        border-radius: 3px;
    }

    .section-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-section {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 3rem 0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .cta-title {
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
    }

    .cta-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    .step-card {
        background-color: white;
        border-radius: 12px;
        padding: 2rem 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        height: 100%;
        position: relative;
        transition: all 0.3s ease;
    }

    .step-card:hover {
        transform: translateY(-10px);
    }

    .step-number {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .step-icon {
        font-size: 2.5rem;
        color: #4361ee;
        margin-bottom: 1.25rem;
    }

    .step-title {
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #212529;
    }

    .step-text {
        color: #6c757d;
        margin-bottom: 0;
    }

    .hero-buttons {
        margin-top: 2rem;
    }
</style>
@endpush
