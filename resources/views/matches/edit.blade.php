@extends('layouts.app')

@section('title', 'Edit Match')

@section('content')

<!-- Link to the FixtureStyle CSS file -->
<link rel="stylesheet" href="{{ asset('css/FixtureStyle.css') }}">

<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item crumbss"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item crumbss"><a href="{{ route('matches.index') }}">Matches</a></li>
                <li class="breadcrumb-item crumbss"><a href="{{ route('matches.show', $match) }}">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Edit Match</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('matches.update', $match) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="home_team_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                        <select class="form-select @error('home_team_id') is-invalid @enderror" id="home_team_id" name="home_team_id" required>
                            <option value="">Select Home Team</option>
                            @foreach($teams as $id => $name)
                                <option value="{{ $id }}" {{ old('home_team_id', $match->home_team_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('home_team_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="away_team_id" class="form-label">Away Team <span class="text-danger">*</span></label>
                        <select class="form-select @error('away_team_id') is-invalid @enderror" id="away_team_id" name="away_team_id" required>
                            <option value="">Select Away Team</option>
                            @foreach($teams as $id => $name)
                                <option value="{{ $id }}" {{ old('away_team_id', $match->away_team_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('away_team_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="match_date" class="form-label">Match Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('match_date') is-invalid @enderror" id="match_date" name="match_date" value="{{ old('match_date', $match->match_date->format('Y-m-d\TH:i')) }}" required>
                        @error('match_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="venue" class="form-label">Venue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue', $match->venue) }}" required>
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="competition" class="form-label">Competition</label>
                        <input type="text" class="form-control @error('competition') is-invalid @enderror" id="competition" name="competition" value="{{ old('competition', $match->competition) }}">
                        @error('competition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="scheduled" {{ old('status', $match->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="live" {{ old('status', $match->status) == 'live' ? 'selected' : '' }}>Live</option>
                            <option value="completed" {{ old('status', $match->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="postponed" {{ old('status', $match->status) == 'postponed' ? 'selected' : '' }}>Postponed</option>
                            <option value="cancelled" {{ old('status', $match->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="home_score" class="form-label">Home Team Score</label>
                        <input type="number" class="form-control @error('home_score') is-invalid @enderror" id="home_score" name="home_score" value="{{ old('home_score', $match->home_score) }}" min="0">
                        @error('home_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="away_score" class="form-label">Away Team Score</label>
                        <input type="number" class="form-control @error('away_score') is-invalid @enderror" id="away_score" name="away_score" value="{{ old('away_score', $match->away_score) }}" min="0">
                        @error('away_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="attendance" class="form-label">Attendance</label>
                        <input type="number" class="form-control @error('attendance') is-invalid @enderror" id="attendance" name="attendance" value="{{ old('attendance', $match->attendance) }}" min="0">
                        @error('attendance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="match_summary" class="form-label">Match Summary</label>
                        <textarea class="form-control @error('match_summary') is-invalid @enderror" id="match_summary" name="match_summary" rows="3">{{ old('match_summary', $match->match_summary) }}</textarea>
                        @error('match_summary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('matches.show', $match) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Match</button>
                </div>
            </form>
        </div>
    </div>
</div