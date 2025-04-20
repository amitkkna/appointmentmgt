@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Patient Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Appointments</h5>
                                    <h2 class="card-text">{{ $totalAppointments }}</h2>
                                    <a href="{{ route('appointments.index') }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Completed Appointments</h5>
                                    <h2 class="card-text">{{ $completedAppointments }}</h2>
                                    <a href="{{ route('appointments.index', ['status' => 'completed']) }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                                                    <th>Doctor</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($upcomingAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->doctor->user->name }}</td>
                                                        <td>{{ $appointment->appointment_date }}</td>
                                                        <td>{{ $appointment->appointment_time }}</td>
                                                        <td>
                                                            @if($appointment->status == 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($appointment->status == 'confirmed')
                                                                <span class="badge bg-success">Confirmed</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No upcoming appointments.</td>
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
                                    <h5 class="mb-0">Past Appointments</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Doctor</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($pastAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->doctor->user->name }}</td>
                                                        <td>{{ $appointment->appointment_date }}</td>
                                                        <td>{{ $appointment->appointment_time }}</td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No past appointments.</td>
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
                                    <h5 class="mb-0">Patient Profile</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Personal Information</h5>
                                            <table class="table">
                                                <tr>
                                                    <th>Name:</th>
                                                    <td>{{ $patient->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $patient->user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{{ $patient->user->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address:</th>
                                                    <td>{{ $patient->user->address }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Medical Information</h5>
                                            <table class="table">
                                                <tr>
                                                    <th>Date of Birth:</th>
                                                    <td>{{ $patient->date_of_birth }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Gender:</th>
                                                    <td>{{ ucfirst($patient->gender) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Blood Group:</th>
                                                    <td>{{ $patient->blood_group ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Allergies:</th>
                                                    <td>{{ $patient->allergies ?? 'None' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary">Edit Profile</a>
                                        <a href="{{ route('patients.medical-history', $patient->id) }}" class="btn btn-info">View Medical History</a>
                                        <a href="{{ route('appointments.create') }}" class="btn btn-success">Book New Appointment</a>
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
