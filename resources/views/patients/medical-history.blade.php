@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Medical History') }} - {{ $patient->user->name }}</span>
                    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-secondary">Back to Profile</a>
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
                            <h5>Patient Information</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $patient->user->name }}</p>
                                    <p><strong>Email:</strong> {{ $patient->user->email }}</p>
                                    <p><strong>Phone:</strong> {{ $patient->user->phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth }}</p>
                                    <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                                    <p><strong>Blood Group:</strong> {{ $patient->blood_group ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Medical History</h5>
                            <hr>
                            
                            @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')
                                <form action="{{ route('patients.update-medical-history', $patient->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="medical_history" class="form-control @error('medical_history') is-invalid @enderror" rows="5" required>{{ old('medical_history', $patient->medical_history) }}</textarea>
                                        
                                        @error('medical_history')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Update Medical History</button>
                                    </div>
                                </form>
                            @else
                                <div class="card">
                                    <div class="card-body">
                                        {{ $patient->medical_history ?? 'No medical history recorded.' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Allergies</h5>
                            <hr>
                            <div class="card">
                                <div class="card-body">
                                    {{ $patient->allergies ?? 'No allergies recorded.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Past Appointments</h5>
                            <hr>
                            
                            @if($appointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Doctor</th>
                                                <th>Specialization</th>
                                                <th>Reason</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->appointment_date }}</td>
                                                    <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                                    <td>{{ $appointment->doctor->specialization->name }}</td>
                                                    <td>{{ $appointment->reason }}</td>
                                                    <td>{{ $appointment->notes ?? 'No notes' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center">No past appointments found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
