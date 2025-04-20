@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Patients') }}</span>
                    <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">Add New Patient</a>
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

                    <div class="mb-3">
                        <form action="{{ route('patients.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('patients.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>{{ $patient->id }}</td>
                                            <td>{{ $patient->user->name }}</td>
                                            <td>{{ $patient->user->email }}</td>
                                            <td>{{ $patient->user->phone }}</td>
                                            <td>{{ ucfirst($patient->gender) }}</td>
                                            <td>
                                                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="{{ route('patients.medical-history', $patient->id) }}" class="btn btn-sm btn-warning">Medical History</a>
                                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $patients->links() }}
                        </div>
                    @else
                        <p class="text-center">No patients found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
