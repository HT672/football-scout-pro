@extends('layouts.app')

@section('title', 'Add New Team')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teams.index') }}">Teams</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Team</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Add New Team</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="logo" class="form-label">Team Logo</label>
                        <input class="form-control @error('logo') is-invalid @enderror" type="file" id="logo" name="logo">
                        <div class="form-text">Upload a square image for best results. Maximum size: 2MB.</div>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="league" class="form-label">League <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('league') is-invalid @enderror" id="league" name="league" value="{{ old('league') }}" required>
                        @error('league')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="manager" class="form-label">Manager</label>
                        <input type="text" class="form-control @error('manager') is-invalid @enderror" id="manager" name="manager" value="{{ old('manager') }}">
                        @error('manager')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="stadium" class="form-label">Stadium</label>
                        <input type="text" class="form-control @error('stadium') is-invalid @enderror" id="stadium" name="stadium" value="{{ old('stadium') }}">
                        @error('stadium')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="founded" class="form-label">Founded Year</label>
                    <input type="number" class="form-control @error('founded') is-invalid @enderror" id="founded" name="founded" value="{{ old('founded') }}" min="1800" max="{{ date('Y') }}">
                    @error('founded')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('teams.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Team</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection