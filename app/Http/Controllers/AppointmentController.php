<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');
        $query = Appointment::query();

        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }

        // Filter appointments based on user role
        if ($user->role === 'admin') {
            // Admin can see all appointments
            $appointments = $query->with(['doctor.user', 'patient.user'])
                ->orderBy('appointment_date', 'desc')
                ->orderBy('appointment_time', 'desc')
                ->paginate(10);
        } elseif ($user->role === 'doctor') {
            // Doctor can only see their appointments
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return redirect()->route('home')->with('error', 'Doctor profile not found.');
            }

            $appointments = $query->where('doctor_id', $doctor->id)
                ->with(['patient.user'])
                ->orderBy('appointment_date', 'desc')
                ->orderBy('appointment_time', 'desc')
                ->paginate(10);
        } elseif ($user->role === 'patient') {
            // Patient can only see their appointments
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return redirect()->route('home')->with('error', 'Patient profile not found.');
            }

            $appointments = $query->where('patient_id', $patient->id)
                ->with(['doctor.user'])
                ->orderBy('appointment_date', 'desc')
                ->orderBy('appointment_time', 'desc')
                ->paginate(10);
        } else {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        return view('appointments.index', compact('appointments', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Only patients can create appointments
        if ($user->role !== 'patient') {
            return redirect()->route('appointments.index')->with('error', 'Only patients can book appointments.');
        }

        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return redirect()->route('home')->with('error', 'Patient profile not found.');
        }

        // Get all doctors for the dropdown
        $doctors = Doctor::with(['user', 'specialization'])->get();

        return view('appointments.create', compact('doctors', 'patient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only patients can create appointments
        if ($user->role !== 'patient') {
            return redirect()->route('appointments.index')->with('error', 'Only patients can book appointments.');
        }

        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return redirect()->route('home')->with('error', 'Patient profile not found.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if the doctor is available at the selected time
        $doctor = Doctor::findOrFail($request->doctor_id);
        $appointmentDate = $request->appointment_date;
        $appointmentTime = $request->appointment_time;

        // Check if the doctor works on the selected day
        $dayOfWeek = strtolower(date('l', strtotime($appointmentDate)));
        if (!in_array($dayOfWeek, $doctor->available_days ?? [])) {
            return redirect()->back()
                ->with('error', 'The doctor is not available on the selected day.')
                ->withInput();
        }

        // Check if the doctor works at the selected time
        if (!in_array($appointmentTime, $doctor->available_time_slots ?? [])) {
            return redirect()->back()
                ->with('error', 'The doctor is not available at the selected time.')
                ->withInput();
        }

        // Check if the doctor already has an appointment at the selected time
        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $appointmentDate)
            ->where('appointment_time', $appointmentTime)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingAppointment) {
            return redirect()->back()
                ->with('error', 'The doctor already has an appointment at the selected time.')
                ->withInput();
        }

        // Create the appointment
        $appointment = new Appointment([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'reason' => $request->reason,
            'status' => 'pending',
            'fee_paid' => 0,
            'payment_status' => 'pending',
        ]);

        $appointment->save();

        return redirect()->route('appointments.show', $appointment->id)
            ->with('success', 'Appointment booked successfully. Please wait for confirmation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $appointment = Appointment::with(['doctor.user', 'doctor.specialization', 'patient.user'])->findOrFail($id);

        // Check if the user has permission to view this appointment
        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient || $appointment->patient_id !== $patient->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }
        } elseif ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor || $appointment->doctor_id !== $doctor->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }
        } elseif ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $appointment = Appointment::with(['doctor.user', 'patient.user'])->findOrFail($id);

        // Check if the user has permission to edit this appointment
        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient || $appointment->patient_id !== $patient->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }

            // Patients can only edit pending appointments
            if ($appointment->status !== 'pending') {
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'You can only edit pending appointments.');
            }
        } elseif ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor || $appointment->doctor_id !== $doctor->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }
        } elseif ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $doctors = Doctor::with(['user', 'specialization'])->get();

        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $appointment = Appointment::findOrFail($id);

        // Check if the user has permission to update this appointment
        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient || $appointment->patient_id !== $patient->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }

            // Patients can only update pending appointments
            if ($appointment->status !== 'pending') {
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'You can only update pending appointments.');
            }

            // Validate the request for patient updates
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:doctors,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'appointment_time' => 'required',
                'reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Check if the doctor is available at the selected time
            $doctor = Doctor::findOrFail($request->doctor_id);
            $appointmentDate = $request->appointment_date;
            $appointmentTime = $request->appointment_time;

            // Check if the doctor works on the selected day
            $dayOfWeek = strtolower(date('l', strtotime($appointmentDate)));
            if (!in_array($dayOfWeek, $doctor->available_days ?? [])) {
                return redirect()->back()
                    ->with('error', 'The doctor is not available on the selected day.')
                    ->withInput();
            }

            // Check if the doctor works at the selected time
            if (!in_array($appointmentTime, $doctor->available_time_slots ?? [])) {
                return redirect()->back()
                    ->with('error', 'The doctor is not available at the selected time.')
                    ->withInput();
            }

            // Check if the doctor already has an appointment at the selected time (excluding this appointment)
            $existingAppointment = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $appointmentDate)
                ->where('appointment_time', $appointmentTime)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $appointment->id)
                ->first();

            if ($existingAppointment) {
                return redirect()->back()
                    ->with('error', 'The doctor already has an appointment at the selected time.')
                    ->withInput();
            }

            // Update the appointment
            $appointment->doctor_id = $doctor->id;
            $appointment->appointment_date = $appointmentDate;
            $appointment->appointment_time = $appointmentTime;
            $appointment->reason = $request->reason;
            $appointment->status = 'pending'; // Reset to pending if patient changes the appointment

        } elseif ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor || $appointment->doctor_id !== $doctor->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }

            // Validate the request for doctor updates
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,completed,cancelled',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update the appointment
            $appointment->status = $request->status;
            $appointment->notes = $request->notes;

            // If the appointment is completed, update the payment status
            if ($request->status === 'completed') {
                $appointment->payment_status = 'paid';
                $appointment->fee_paid = $doctor->consultation_fee;
            }

        } elseif ($user->role === 'admin') {
            // Validate the request for admin updates
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:doctors,id',
                'appointment_date' => 'required|date',
                'appointment_time' => 'required',
                'status' => 'required|in:pending,confirmed,completed,cancelled',
                'reason' => 'required|string|max:500',
                'notes' => 'nullable|string|max:1000',
                'payment_status' => 'required|in:pending,paid',
                'fee_paid' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update the appointment
            $appointment->doctor_id = $request->doctor_id;
            $appointment->appointment_date = $request->appointment_date;
            $appointment->appointment_time = $request->appointment_time;
            $appointment->status = $request->status;
            $appointment->reason = $request->reason;
            $appointment->notes = $request->notes;
            $appointment->payment_status = $request->payment_status;
            $appointment->fee_paid = $request->fee_paid;

        } else {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $appointment->save();

        return redirect()->route('appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $appointment = Appointment::findOrFail($id);

        // Check if the user has permission to cancel this appointment
        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient || $appointment->patient_id !== $patient->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }

            // Patients can only cancel pending appointments
            if ($appointment->status !== 'pending') {
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'You can only cancel pending appointments.');
            }
        } elseif ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor || $appointment->doctor_id !== $doctor->id) {
                return redirect()->route('appointments.index')->with('error', 'Unauthorized access.');
            }
        } elseif ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // Cancel the appointment instead of deleting it
        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Search for available doctors.
     */
    public function searchDoctors(Request $request)
    {
        $specialization_id = $request->input('specialization_id');
        $date = $request->input('date');

        $query = Doctor::with(['user', 'specialization']);

        // Filter by specialization if provided
        if ($specialization_id) {
            $query->where('specialization_id', $specialization_id);
        }

        // Get all doctors matching the criteria
        $doctors = $query->get();

        // Filter doctors by availability on the selected date
        if ($date) {
            $dayOfWeek = strtolower(date('l', strtotime($date)));

            $doctors = $doctors->filter(function ($doctor) use ($dayOfWeek) {
                return in_array($dayOfWeek, $doctor->available_days ?? []);
            });
        }

        return response()->json([
            'doctors' => $doctors
        ]);
    }
}
