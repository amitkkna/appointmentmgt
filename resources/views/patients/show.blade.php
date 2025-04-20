@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Patient Profile') }}</span>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('patients.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                    @endif
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

                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="mb-3">
                                <img src="{{ $patient->user->profile_image ? asset('storage/' . $patient->user->profile_image) : asset('images/default-patient.png') }}" alt="Patient Profile" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                            </div>
                            <h4>{{ $patient->user->name }}</h4>
                            
                            <div class="mt-3">
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary">Edit Profile</a>
                                <a href="{{ route('patients.medical-history', $patient->id) }}" class="btn btn-info">Medical History</a>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <h5>Personal Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> {{ $patient->user->email }}</p>
                                    <p><strong>Phone:</strong> {{ $patient->user->phone }}</p>
                                    <p><strong>Address:</strong> {{ $patient->user->address }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth }}</p>
                                    <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                                    <p><strong>Blood Group:</strong> {{ $patient->blood_group ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            
                            <h5>Medical Information</h5>
                            <hr>
                            <div class="mb-3">
                                <p><strong>Allergies:</strong></p>
                                <p>{{ $patient->allergies ?? 'No allergies recorded.' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <p><strong>Medical History:</strong></p>
                                <p>{{ Str::limit($patient->medical_history, 200) ?? 'No medical history recorded.' }}</p>
                                <a href="{{ route('patients.medical-history', $patient->id) }}">View Full Medical History</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Recent Appointments</h5>
                            <hr>
                            
                            @if($appointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->appointment_date }}</td>
                                                    <td>{{ $appointment->appointment_time }}</td>
                                                    <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $appointment->status == 'pending' ? 'warning' : ($appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'info')) }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $appointments->links() }}
                                </div>
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('appointments.index') }}" class="btn btn-primary">View All Appointments</a>
                                    
                                    @if(Auth::user()->role === 'patient')
                                        <a href="{{ route('appointments.create') }}" class="btn btn-success">Book New Appointment</a>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">No appointments found.</p>
                                
                                @if(Auth::user()->role === 'patient')
                                    <div class="text-center mt-3">
                                        <a href="{{ route('appointments.create') }}" class="btn btn-success">Book New Appointment</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
