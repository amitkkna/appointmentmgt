@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Doctor Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Patients</h5>
                                    <h2 class="card-text">{{ $totalPatients }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Appointments</h5>
                                    <h2 class="card-text">{{ $totalAppointments }}</h2>
                                    <a href="{{ route('appointments.index') }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Reviews</h5>
                                    <h2 class="card-text">{{ $totalReviews }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Today's Appointments</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Patient</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($todayAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->patient->user->name }}</td>
                                                        <td>{{ $appointment->appointment_time }}</td>
                                                        <td>
                                                            @if($appointment->status == 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($appointment->status == 'confirmed')
                                                                <span class="badge bg-success">Confirmed</span>
                                                            @elseif($appointment->status == 'completed')
                                                                <span class="badge bg-primary">Completed</span>
                                                            @elseif($appointment->status == 'cancelled')
                                                                <span class="badge bg-danger">Cancelled</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No appointments for today.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Upcoming Appointments</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Patient</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($upcomingAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->patient->user->name }}</td>
                                                        <td>{{ $appointment->appointment_date }}</td>
                                                        <td>{{ $appointment->appointment_time }}</td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No upcoming appointments.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Doctor Profile</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Personal Information</h5>
                                            <table class="table">
                                                <tr>
                                                    <th>Name:</th>
                                                    <td>{{ $doctor->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $doctor->user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{{ $doctor->user->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address:</th>
                                                    <td>{{ $doctor->user->address }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Professional Information</h5>
                                            <table class="table">
                                                <tr>
                                                    <th>Specialization:</th>
                                                    <td>{{ $doctor->specialization->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Qualification:</th>
                                                    <td>{{ $doctor->qualification }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Experience:</th>
                                                    <td>{{ $doctor->experience_years }} years</td>
                                                </tr>
                                                <tr>
                                                    <th>Consultation Fee:</th>
                                                    <td>${{ $doctor->consultation_fee }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-primary">Edit Profile</a>
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
@endsection
