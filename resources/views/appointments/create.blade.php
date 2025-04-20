@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Book New Appointment') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="doctor_id" class="col-md-4 col-form-label text-md-end">{{ __('Doctor') }}</label>

                            <div class="col-md-6">
                                <select id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->user->name }} ({{ $doctor->specialization->name }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('doctor_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="appointment_date" class="col-md-4 col-form-label text-md-end">{{ __('Appointment Date') }}</label>

                            <div class="col-md-6">
                                <input id="appointment_date" type="date" class="form-control @error('appointment_date') is-invalid @enderror" name="appointment_date" value="{{ old('appointment_date') }}" required min="{{ date('Y-m-d') }}">

                                @error('appointment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="appointment_time" class="col-md-4 col-form-label text-md-end">{{ __('Appointment Time') }}</label>

                            <div class="col-md-6">
                                <select id="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" name="appointment_time" required>
                                    <option value="">Select Time</option>
                                    <!-- Time slots will be populated via JavaScript -->
                                </select>

                                @error('appointment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="reason" class="col-md-4 col-form-label text-md-end">{{ __('Reason for Visit') }}</label>

                            <div class="col-md-6">
                                <textarea id="reason" class="form-control @error('reason') is-invalid @enderror" name="reason" required>{{ old('reason') }}</textarea>

                                @error('reason')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Book Appointment') }}
                                </button>
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');
        
        // Store doctor data
        let doctorsData = {};
        
        @foreach($doctors as $doctor)
            doctorsData[{{ $doctor->id }}] = {
                availableDays: @json($doctor->available_days),
                availableTimeSlots: @json($doctor->available_time_slots)
            };
        @endforeach
        
        // Function to update available time slots
        function updateTimeSlots() {
            const doctorId = doctorSelect.value;
            const date = dateInput.value;
            
            // Clear current options
            timeSelect.innerHTML = '<option value="">Select Time</option>';
            
            if (!doctorId || !date) return;
            
            const doctor = doctorsData[doctorId];
            if (!doctor) return;
            
            // Check if doctor works on selected day
            const dayOfWeek = new Date(date).toLocaleDateString('en-US', { weekday: 'lowercase' });
            if (!doctor.availableDays.includes(dayOfWeek)) {
                alert('The doctor is not available on the selected day.');
                dateInput.value = '';
                return;
            }
            
            // Add available time slots
            doctor.availableTimeSlots.forEach(timeSlot => {
                const option = document.createElement('option');
                option.value = timeSlot;
                option.textContent = timeSlot;
                timeSelect.appendChild(option);
            });
        }
        
        // Add event listeners
        doctorSelect.addEventListener('change', updateTimeSlots);
        dateInput.addEventListener('change', updateTimeSlots);
    });
</script>
@endpush
@endsection
