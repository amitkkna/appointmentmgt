@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Doctors</h5>
                                    <h2 class="card-text">{{ $doctorsCount }}</h2>
                                    <a href="{{ route('doctors.index') }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Patients</h5>
                                    <h2 class="card-text">{{ $patientsCount }}</h2>
                                    <a href="{{ route('patients.index') }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Appointments</h5>
                                    <h2 class="card-text">{{ $appointmentsCount }}</h2>
                                    <a href="{{ route('appointments.index') }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Appointments</h5>
                                    <h2 class="card-text">{{ $pendingAppointments }}</h2>
                                    <a href="{{ route('appointments.index', ['status' => 'pending']) }}" class="text-white">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Appointments</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Patient</th>
                                                    <th>Doctor</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->id }}</td>
                                                        <td>{{ $appointment->patient->user->name }}</td>
                                                        <td>{{ $appointment->doctor->user->name }}</td>
                                                        <td>{{ $appointment->appointment_date }}</td>
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
                                                        <td colspan="7" class="text-center">No appointments found.</td>
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
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ route('doctors.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-user-md"></i> Add New Doctor
                                        </a>
                                        <a href="{{ route('patients.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-user"></i> Add New Patient
                                        </a>
                                        <a href="{{ route('appointments.index', ['status' => 'pending']) }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-clock"></i> View Pending Appointments
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">System Information</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Laravel Version
                                            <span class="badge bg-primary">{{ app()->version() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            PHP Version
                                            <span class="badge bg-primary">{{ phpversion() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Server Time
                                            <span class="badge bg-primary">{{ now() }}</span>
                                        </li>
                                    </ul>
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
