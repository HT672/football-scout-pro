@extends('layouts.app')

@section('title', 'Add New Player')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('players.index') }}">Players</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Player</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Add New Player</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality') }}" required>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="jersey_number" class="form-label">Jersey Number</label>
                        <input type="number" class="form-control @error('jersey_number') is-invalid @enderror" id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" min="1" max="99">
                        @error('jersey_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="team_id" class="form-label">Team</label>
                        <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id">
                            <option value="">No Team</option>
                            @foreach($teams as $id => $name)
                                <option value="{{ $id }}" {{ old('team_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('team_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="position_id" class="form-label">Position</label>
                        <select class="form-select @error('position_id') is-invalid @enderror" id="position_id" name="position_id">
                            <option value="">Select Position</option>
                            @foreach($positions as $id => $name)
                                <option value="{{ $id }}" {{ old('position_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="height" class="form-label">Height (cm)</label>
                        <input type="number" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height') }}" min="140" max="220">
                        @error('height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" min="40" max="130">
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="preferred_foot" class="form-label">Preferred Foot <span class="text-danger">*</span></label>
                        <select class="form-select @error('preferred_foot') is-invalid @enderror" id="preferred_foot" name="preferred_foot" required>
                            <option value="right" {{ old('preferred_foot') == 'right' ? 'selected' : '' }}>Right</option>
                            <option value="left" {{ old('preferred_foot') == 'left' ? 'selected' : '' }}>Left</option>
                            <option value="both" {{ old('preferred_foot') == 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                        @error('preferred_foot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="market_value" class="form-label">Market Value (in millions €)</label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control @error('market_value') is-invalid @enderror" id="market_value" name="market_value" value="{{ old('market_value') }}" min="0" step="0.1">
                            <span class="input-group-text">M</span>
                        </div>
                        @error('market_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="photo" class="form-label">Player Photo</label>
                        <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo">
                        <div class="form-text">Upload a square image for best results. Maximum size: 2MB.</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="bio" class="form-label">Biography</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4">{{ old('bio') }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('players.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Player</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection