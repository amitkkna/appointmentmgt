<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class)->only(['index', 'create', 'store', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->input('name');

        $query = Patient::with('user');

        // Filter by name if provided
        if ($name) {
            $query->whereHas('user', function($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        $patients = $query->paginate(15);

        return view('patients.index', compact('patients', 'name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create the patient profile
        $patient = Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_history' => $request->medical_history,
        ]);

        return redirect()->route('patients.show', $patient->id)
            ->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $patient = Patient::with('user')->findOrFail($id);

        // Check if the user has permission to view this patient
        if ($user->role === 'patient' && $patient->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $appointments = Appointment::where('patient_id', $id)
            ->with(['doctor.user', 'doctor.specialization'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('patients.show', compact('patient', 'appointments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $patient = Patient::with('user')->findOrFail($id);

        // Check if the user has permission to edit this patient
        if ($user->role === 'patient' && $patient->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $patient = Patient::with('user')->findOrFail($id);

        // Check if the user has permission to update this patient
        if ($user->role === 'patient' && $patient->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $patient->user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the user
        $patient->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update the patient profile
        $patient->update([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_history' => $request->medical_history,
        ]);

        return redirect()->route('patients.show', $patient->id)
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);

        // Check if the patient has any appointments
        if ($patient->appointments()->count() > 0) {
            return redirect()->route('patients.index')
                ->with('error', 'Cannot delete patient with existing appointments.');
        }

        // Delete the user (will cascade delete the patient profile)
        $patient->user->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    /**
     * Show the medical history of the patient.
     */
    public function medicalHistory(string $id)
    {
        $user = auth()->user();
        $patient = Patient::with('user')->findOrFail($id);

        // Check if the user has permission to view this patient's medical history
        if ($user->role === 'patient' && $patient->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $appointments = Appointment::where('patient_id', $id)
            ->where('status', 'completed')
            ->with(['doctor.user', 'doctor.specialization'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patients.medical-history', compact('patient', 'appointments'));
    }

    /**
     * Update the medical history of the patient.
     */
    public function updateMedicalHistory(Request $request, string $id)
    {
        $user = auth()->user();
        $patient = Patient::findOrFail($id);

        // Check if the user has permission to update this patient's medical history
        if ($user->role === 'patient' && $patient->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // Only doctors and admins can update medical history
        if ($user->role === 'patient') {
            return redirect()->route('patients.medical-history', $id)
                ->with('error', 'Only doctors and admins can update medical history.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'medical_history' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the patient's medical history
        $patient->update([
            'medical_history' => $request->medical_history,
        ]);

        return redirect()->route('patients.medical-history', $id)
            ->with('success', 'Medical history updated successfully.');
    }
}
