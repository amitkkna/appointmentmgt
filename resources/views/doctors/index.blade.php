@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Doctors') }}</span>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-sm">Add New Doctor</a>
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

                    <div class="mb-4">
                        <form action="{{ route('doctors.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <select name="specialization_id" class="form-select">
                                    <option value="">All Specializations</option>
                                    @foreach($specializations as $specialization)
                                        <option value="{{ $specialization->id }}" {{ request('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                            {{ $specialization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    @if($doctors->count() > 0)
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            @foreach($doctors as $doctor)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Dr. {{ $doctor->user->name }}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">{{ $doctor->specialization->name }}</h6>
                                            <p class="card-text">
                                                <strong>Qualification:</strong> {{ $doctor->qualification }}<br>
                                                <strong>Experience:</strong> {{ $doctor->experience_years }} years<br>
                                                <strong>Consultation Fee:</strong> ${{ number_format($doctor->consultation_fee, 2) }}
                                            </p>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-info btn-sm">View Profile</a>

                                                @if(auth()->check() && auth()->user()->role === 'admin')
                                                    <div>
                                                        <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</button>
                                                        </form>
                                                    </div>
                                                @endif

                                                @if(auth()->check() && auth()->user()->role === 'patient')
                                                    <a href="{{ route('appointments.create') }}" class="btn btn-success btn-sm">Book Appointment</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $doctors->links() }}
                        </div>
                    @else
                        <p class="text-center">No doctors found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
