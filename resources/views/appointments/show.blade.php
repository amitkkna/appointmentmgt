@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Appointment Details') }}</span>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Appointment #{{ $appointment->id }}</h5>
                                <span class="badge bg-{{ $appointment->status == 'pending' ? 'warning' : ($appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'info')) }} fs-6">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            <hr>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Doctor Information</h6>
                            <p><strong>Name:</strong> Dr. {{ $appointment->doctor->user->name }}</p>
                            <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization->name }}</p>
                            <p><strong>Qualification:</strong> {{ $appointment->doctor->qualification }}</p>
                            <p><strong>Experience:</strong> {{ $appointment->doctor->experience_years }} years</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Patient Information</h6>
                            <p><strong>Name:</strong> {{ $appointment->patient->user->name }}</p>
                            <p><strong>Email:</strong> {{ $appointment->patient->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $appointment->patient->user->phone }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6>Appointment Details</h6>
                            <p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>
                            <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
                            <p><strong>Reason for Visit:</strong> {{ $appointment->reason }}</p>

                            @if($appointment->notes)
                                <p><strong>Doctor's Notes:</strong> {{ $appointment->notes }}</p>
                            @endif

                            <p><strong>Payment Status:</strong>
                                <span class="badge bg-{{ $appointment->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($appointment->payment_status) }}
                                </span>
                            </p>

                            @if($appointment->fee_paid > 0)
                                <p><strong>Fee Paid:</strong> ${{ number_format($appointment->fee_paid, 2) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                @if(auth()->user()->role === 'patient' && $appointment->status === 'pending')
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</button>
                                    </form>
                                @endif

                                @if(auth()->user()->role === 'doctor' && ($appointment->status === 'pending' || $appointment->status === 'confirmed'))
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary">Update Status</a>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
