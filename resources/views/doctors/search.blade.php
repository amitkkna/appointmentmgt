@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Find a Doctor') }}</div>

                <div class="card-body">
                    <div class="mb-4">
                        <form action="{{ route('doctors.search') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="specialization_id" class="form-select">
                                    <option value="">All Specializations</option>
                                    @foreach($specializations as $specialization)
                                        <option value="{{ $specialization->id }}" {{ request('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                            {{ $specialization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" placeholder="Available on date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('doctors.search') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    @if(isset($doctors) && $doctors->count() > 0)
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
                                            <p class="card-text">
                                                <strong>Available Days:</strong> 
                                                @if($doctor->available_days)
                                                    {{ implode(', ', array_map('ucfirst', $doctor->available_days)) }}
                                                @else
                                                    Not specified
                                                @endif
                                            </p>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-info btn-sm">View Profile</a>
                                                
                                                @if(Auth::check() && Auth::user()->role === 'patient')
                                                    <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}" class="btn btn-success btn-sm">Book Appointment</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($doctors))
                        <p class="text-center">No doctors found matching your criteria.</p>
                    @else
                        <p class="text-center">Please use the search form to find doctors.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
