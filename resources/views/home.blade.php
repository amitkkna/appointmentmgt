@extends('layouts.app')

@section('content')

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4">Your Health, Our Priority</h1>
            <p class="lead">Book appointments with the best doctors in your area with just a few clicks.</p>
            <a href="{{ route('doctors.search') }}" class="btn btn-light btn-lg mt-3">Get Started</a>
        </div>
    </section>

    <div class="container">
        <h2 class="text-center mb-5">Our Services</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <div class="card-icon">📅</div>
                        <h5 class="card-title">Easy Appointment Booking</h5>
                        <p class="card-text">Book appointments with your preferred doctors at your convenient time.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <div class="card-icon">👨‍⚕️</div>
                        <h5 class="card-title">Expert Doctors</h5>
                        <p class="card-text">Access to a wide range of specialized doctors with years of experience.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <div class="card-icon">🔔</div>
                        <h5 class="card-title">Appointment Reminders</h5>
                        <p class="card-text">Get timely reminders for your upcoming appointments.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <div class="card-icon">📋</div>
                        <h5 class="card-title">Medical History</h5>
                        <p class="card-text">Keep track of your medical history and share it with your doctors.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <div class="card-icon">⭐</div>
                        <h5 class="card-title">Doctor Reviews</h5>
                        <p class="card-text">Read reviews from other patients and share your own experience.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <div class="card-icon">💰</div>
                        <h5 class="card-title">Online Payment</h5>
                        <p class="card-text">Secure online payment for your appointments and consultations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .hero-section {
        background-color: #3490dc;
        color: white;
        padding: 80px 0;
        margin-bottom: 40px;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        margin-bottom: 20px;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #3490dc;
    }
</style>
@endpush
