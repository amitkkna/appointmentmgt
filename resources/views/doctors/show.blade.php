@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Doctor Profile') }}</span>
                    <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="mb-3">
                                <img src="{{ $doctor->user->profile_image ? asset('storage/' . $doctor->user->profile_image) : asset('images/default-doctor.png') }}" alt="Doctor Profile" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                            </div>
                            <h4>Dr. {{ $doctor->user->name }}</h4>
                            <h6 class="text-muted">{{ $doctor->specialization->name }}</h6>
                            
                            <div class="mt-3">
                                <div class="d-flex justify-content-center">
                                    <div class="me-2">
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($averageRating))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-muted">({{ number_format($averageRating, 1) }} / 5)</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(Auth::check() && Auth::user()->role === 'patient')
                                <div class="mt-3">
                                    <a href="{{ route('appointments.create') }}" class="btn btn-success">Book Appointment</a>
                                </div>
                            @endif
                            
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <div class="mt-3">
                                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-primary">Edit Profile</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h5>Professional Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Qualification:</strong> {{ $doctor->qualification }}</p>
                                    <p><strong>Experience:</strong> {{ $doctor->experience_years }} years</p>
                                    <p><strong>License Number:</strong> {{ $doctor->license_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Consultation Fee:</strong> ${{ number_format($doctor->consultation_fee, 2) }}</p>
                                    <p><strong>Available Days:</strong> 
                                        @if($doctor->available_days)
                                            {{ implode(', ', array_map('ucfirst', $doctor->available_days)) }}
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                    <p><strong>Available Time Slots:</strong> 
                                        @if($doctor->available_time_slots)
                                            {{ implode(', ', $doctor->available_time_slots) }}
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <h5>About</h5>
                            <hr>
                            <p>{{ $doctor->bio ?? 'No bio information available.' }}</p>
                            
                            <h5>Contact Information</h5>
                            <hr>
                            <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $doctor->user->phone }}</p>
                            <p><strong>Address:</strong> {{ $doctor->user->address }}</p>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Patient Reviews</h5>
                            <hr>
                            
                            @if($reviews->count() > 0)
                                @foreach($reviews as $review)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-title">{{ $review->patient->user->name }}</h6>
                                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                            <p class="card-text">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $reviews->links() }}
                                </div>
                            @else
                                <p class="text-center">No reviews yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
