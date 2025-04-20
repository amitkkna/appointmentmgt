@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Appointment') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
                        @csrf
                        @method('PUT')

                        @if(Auth::user()->role === 'patient' || Auth::user()->role === 'admin')
                            <div class="row mb-3">
                                <label for="doctor_id" class="col-md-4 col-form-label text-md-end">{{ __('Doctor') }}</label>

                                <div class="col-md-6">
                                    <select id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" name="doctor_id" required {{ Auth::user()->role === 'doctor' ? 'disabled' : '' }}>
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
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
                                    <input id="appointment_date" type="date" class="form-control @error('appointment_date') is-invalid @enderror" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}" required min="{{ Auth::user()->role === 'admin' ? '' : date('Y-m-d') }}">

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
                                        <option value="{{ $appointment->appointment_time }}" selected>{{ $appointment->appointment_time }}</option>
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
                                    <textarea id="reason" class="form-control @error('reason') is-invalid @enderror" name="reason" required>{{ old('reason', $appointment->reason) }}</textarea>

                                    @error('reason')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')
                            <div class="row mb-3">
                                <label for="status" class="col-md-4 col-form-label text-md-end">{{ __('Status') }}</label>

                                <div class="col-md-6">
                                    <select id="status" class="form-select @error('status') is-invalid @enderror" name="status" required>
                                        <option value="pending" {{ old('status', $appointment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ old('status', $appointment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $appointment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>

                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="notes" class="col-md-4 col-form-label text-md-end">{{ __('Doctor\'s Notes') }}</label>

                                <div class="col-md-6">
                                    <textarea id="notes" class="form-control @error('notes') is-invalid @enderror" name="notes">{{ old('notes', $appointment->notes) }}</textarea>

                                    @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <div class="row mb-3">
                                <label for="payment_status" class="col-md-4 col-form-label text-md-end">{{ __('Payment Status') }}</label>

                                <div class="col-md-6">
                                    <select id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" name="payment_status" required>
                                        <option value="pending" {{ old('payment_status', $appointment->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ old('payment_status', $appointment->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>

                                    @error('payment_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="fee_paid" class="col-md-4 col-form-label text-md-end">{{ __('Fee Paid') }}</label>

                                <div class="col-md-6">
                                    <input id="fee_paid" type="number" step="0.01" min="0" class="form-control @error('fee_paid') is-invalid @enderror" name="fee_paid" value="{{ old('fee_paid', $appointment->fee_paid) }}" required>

                                    @error('fee_paid')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Appointment') }}
                                </button>
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-secondary">
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
        @if(Auth::user()->role === 'patient' || Auth::user()->role === 'admin')
            const doctorSelect = document.getElementById('doctor_id');
            const dateInput = document.getElementById('appointment_date');
            const timeSelect = document.getElementById('appointment_time');
            const currentTime = "{{ $appointment->appointment_time }}";
            
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
                
                // Save the current selection
                const currentSelection = timeSelect.value;
                
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
                    if (timeSlot === currentSelection || timeSlot === currentTime) {
                        option.selected = true;
                    }
                    timeSelect.appendChild(option);
                });
            }
            
            // Add event listeners
            doctorSelect.addEventListener('change', updateTimeSlots);
            dateInput.addEventListener('change', updateTimeSlots);
            
            // Initialize time slots
            updateTimeSlots();
        @endif
    });
</script>
@endpush
@endsection
