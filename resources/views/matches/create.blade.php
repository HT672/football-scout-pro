@extends('layouts.app')

@section('title', 'Schedule New Match')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.index') }}">Matches</a></li>
                <li class="breadcrumb-item active" aria-current="page">Schedule New Match</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Schedule New Match</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('matches.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="home_team_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                        <select class="form-select @error('home_team_id') is-invalid @enderror" id="home_team_id" name="home_team_id" required>
                            <option value="">Select Home Team</option>
                            @foreach($teams as $id => $name)
                                <option value="{{ $id }}" {{ old('home_team_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                                <option value="{{ $id }}" {{ old('away_team_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                        <input type="datetime-local" class="form-control @error('match_date') is-invalid @enderror" id="match_date" name="match_date" value="{{ old('match_date') }}" required>
                        @error('match_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="venue" class="form-label">Venue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue') }}" required>
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="competition" class="form-label">Competition</label>
                        <input type="text" class="form-control @error('competition') is-invalid @enderror" id="competition" name="competition" value="{{ old('competition') }}">
                        @error('competition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="live" {{ old('status') == 'live' ? 'selected' : '' }}>Live</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="postponed" {{ old('status') == 'postponed' ? 'selected' : '' }}>Postponed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('matches.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Schedule Match</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const homeTeamSelect = document.getElementById('home_team_id');
        const awayTeamSelect = document.getElementById('away_team_id');
        
        // Prevent selecting the same team for home and away
        homeTeamSelect.addEventListener('change', function() {
            const selectedHomeTeam = homeTeamSelect.value;
            
            // Enable all options in away team select
            for (const option of awayTeamSelect.options) {
                option.disabled = false;
            }
            
            // Disable the option corresponding to the selected home team
            if (selectedHomeTeam) {
                const awayOption = awayTeamSelect.querySelector(`option[value="${selectedHomeTeam}"]`);
                if (awayOption) {
                    awayOption.disabled = true;
                }
                
                // If currently selected away team is now disabled, reset selection
                if (awayTeamSelect.value === selectedHomeTeam) {
                    awayTeamSelect.value = '';
                }
            }
        });
        
        awayTeamSelect.addEventListener('change', function() {
            const selectedAwayTeam = awayTeamSelect.value;
            
            // Enable all options in home team select
            for (const option of homeTeamSelect.options) {
                option.disabled = false;
            }
            
            // Disable the option corresponding to the selected away team
            if (selectedAwayTeam) {
                const homeOption = homeTeamSelect.querySelector(`option[value="${selectedAwayTeam}"]`);
                if (homeOption) {
                    homeOption.disabled = true;
                }
                
                // If currently selected home team is now disabled, reset selection
                if (homeTeamSelect.value === selectedAwayTeam) {
                    homeTeamSelect.value = '';
                }
            }
        });
        
        // Initialize on page load
        if (homeTeamSelect.value) {
            const homeEvent = new Event('change');
            homeTeamSelect.dispatchEvent(homeEvent);
        }
        
        if (awayTeamSelect.value) {
            const awayEvent = new Event('change');
            awayTeamSelect.dispatchEvent(awayEvent);
        }
    });
</script>
@endsection