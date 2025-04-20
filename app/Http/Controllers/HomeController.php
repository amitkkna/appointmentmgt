<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Specialization;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Only require authentication for dashboard routes
        $this->middleware('auth')->except(['index', 'welcome']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $specializations = Specialization::all();
        $featuredDoctors = Doctor::with(['user', 'specialization'])
            ->take(6)
            ->get();

        return view('home', compact('specializations', 'featuredDoctors'));
    }

    /**
     * Show the welcome page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        return view('welcome');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        $doctorsCount = Doctor::count();
        $patientsCount = Patient::count();
        $appointmentsCount = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();

        $recentAppointments = Appointment::with(['doctor.user', 'patient.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'doctorsCount',
            'patientsCount',
            'appointmentsCount',
            'pendingAppointments',
            'recentAppointments'
        ));
    }

    /**
     * Show the doctor dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function doctorDashboard()
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Doctor profile not found.');
        }

        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', now()->format('Y-m-d'))
            ->with('patient.user')
            ->get();

        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', '>', now()->format('Y-m-d'))
            ->with('patient.user')
            ->take(5)
            ->get();

        $totalPatients = $doctor->appointments()->distinct('patient_id')->count('patient_id');
        $totalAppointments = $doctor->appointments()->count();
        $totalReviews = Review::where('doctor_id', $doctor->id)->count();

        return view('doctor.dashboard', compact(
            'doctor',
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'totalAppointments',
            'totalReviews'
        ));
    }

    /**
     * Show the patient dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function patientDashboard()
    {
        $patient = Patient::where('user_id', auth()->id())->first();

        if (!$patient) {
            return redirect()->route('home')->with('error', 'Patient profile not found.');
        }

        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', 'confirmed');
            })
            ->with('doctor.user')
            ->take(5)
            ->get();

        $pastAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->with('doctor.user')
            ->latest()
            ->take(5)
            ->get();

        $totalAppointments = Appointment::where('patient_id', $patient->id)->count();
        $completedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->count();

        return view('patient.dashboard', compact(
            'patient',
            'upcomingAppointments',
            'pastAppointments',
            'totalAppointments',
            'completedAppointments'
        ));
    }
}
