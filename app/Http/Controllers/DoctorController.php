<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $specialization_id = $request->input('specialization_id');
        $name = $request->input('name');

        $query = Doctor::with(['user', 'specialization']);

        // Filter by specialization if provided
        if ($specialization_id) {
            $query->where('specialization_id', $specialization_id);
        }

        // Filter by name if provided
        if ($name) {
            $query->whereHas('user', function($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        $doctors = $query->paginate(12);
        $specializations = Specialization::all();

        return view('doctors.index', compact('doctors', 'specializations', 'specialization_id', 'name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specializations = Specialization::all();
        return view('doctors.create', compact('specializations'));
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
            'specialization_id' => 'required|exists:specializations,id',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'license_number' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'available_days' => 'required|array|min:1',
            'available_time_slots' => 'required|array|min:1',
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
            'role' => 'doctor',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create the doctor profile
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $request->specialization_id,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'license_number' => $request->license_number,
            'bio' => $request->bio,
            'consultation_fee' => $request->consultation_fee,
            'available_days' => $request->available_days,
            'available_time_slots' => $request->available_time_slots,
        ]);

        return redirect()->route('doctors.show', $doctor->id)
            ->with('success', 'Doctor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::with(['user', 'specialization'])->findOrFail($id);
        $reviews = Review::where('doctor_id', $id)
            ->with('patient.user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $averageRating = Review::where('doctor_id', $id)->avg('rating') ?? 0;

        return view('doctors.show', compact('doctor', 'reviews', 'averageRating'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        $specializations = Specialization::all();

        return view('doctors.edit', compact('doctor', 'specializations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'specialization_id' => 'required|exists:specializations,id',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'license_number' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'available_days' => 'required|array|min:1',
            'available_time_slots' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the user
        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update the doctor profile
        $doctor->update([
            'specialization_id' => $request->specialization_id,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'license_number' => $request->license_number,
            'bio' => $request->bio,
            'consultation_fee' => $request->consultation_fee,
            'available_days' => $request->available_days,
            'available_time_slots' => $request->available_time_slots,
        ]);

        return redirect()->route('doctors.show', $doctor->id)
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = Doctor::findOrFail($id);

        // Check if the doctor has any appointments
        if ($doctor->appointments()->count() > 0) {
            return redirect()->route('doctors.index')
                ->with('error', 'Cannot delete doctor with existing appointments.');
        }

        // Delete the user (will cascade delete the doctor profile)
        $doctor->user->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }

    /**
     * Search for doctors.
     */
    public function search(Request $request)
    {
        $specialization_id = $request->input('specialization_id');
        $name = $request->input('name');
        $date = $request->input('date');

        $query = Doctor::with(['user', 'specialization']);

        // Filter by specialization if provided
        if ($specialization_id) {
            $query->where('specialization_id', $specialization_id);
        }

        // Filter by name if provided
        if ($name) {
            $query->whereHas('user', function($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
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

        $specializations = Specialization::all();

        return view('doctors.search', compact('doctors', 'specializations', 'specialization_id', 'name', 'date'));
    }
}
