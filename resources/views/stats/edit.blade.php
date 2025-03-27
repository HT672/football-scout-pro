@extends('layouts.app')

@section('title', 'Edit Player Statistics')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('stats.index') }}">Stats</a></li>
                <li class="breadcrumb-item"><a href="{{ route('stats.show', $stat->player_id) }}">{{ $stat->player->full_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Statistics</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Edit Player Statistics</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('stats.update', $stat) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="player_id" class="form-label">Player <span class="text-danger">*</span></label>
                        <select class="form-select @error('player_id') is-invalid @enderror" id="player_id" name="player_id" required>
                            <option value="">Select Player</option>
                            @foreach($players as $id => $name)
                                <option value="{{ $id }}" {{ old('player_id', $stat->player_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('player_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="match_id" class="form-label">Match</label>
                        <select class="form-select @error('match_id') is-invalid @enderror" id="match_id" name="match_id">
                            <option value="">Select Match (Optional)</option>
                            @if($stat->match)
                                <option value="{{ $stat->match_id }}" selected>
                                    {{ $stat->match->homeTeam->name }} vs {{ $stat->match->awayTeam->name }} ({{ $stat->match->match_date->format('M d, Y') }})
                                </option>
                            @endif
                        </select>
                        @error('match_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="season" class="form-label">Season <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('season') is-invalid @enderror" id="season" name="season" value="{{ old('season', $stat->season) }}" required>
                        @error('season')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="minutes_played" class="form-label">Minutes Played <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('minutes_played') is-invalid @enderror" id="minutes_played" name="minutes_played" value="{{ old('minutes_played', $stat->minutes_played) }}" min="0" max="120" required>
                        @error('minutes_played')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="goals" class="form-label">Goals <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('goals') is-invalid @enderror" id="goals" name="goals" value="{{ old('goals', $stat->goals) }}" min="0" required>
                        @error('goals')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="assists" class="form-label">Assists <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('assists') is-invalid @enderror" id="assists" name="assists" value="{{ old('assists', $stat->assists) }}" min="0" required>
                        @error('assists')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="yellow_cards" class="form-label">Yellow Cards <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('yellow_cards') is-invalid @enderror" id="yellow_cards" name="yellow_cards" value="{{ old('yellow_cards', $stat->yellow_cards) }}" min="0" required>
                        @error('yellow_cards')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="red_cards" class="form-label">Red Cards <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('red_cards') is-invalid @enderror" id="red_cards" name="red_cards" value="{{ old('red_cards', $stat->red_cards) }}" min="0" required>
                        @error('red_cards')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="passes" class="form-label">Passes <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('passes') is-invalid @enderror" id="passes" name="passes" value="{{ old('passes', $stat->passes) }}" min="0" required>
                        @error('passes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="pass_accuracy" class="form-label">Pass Accuracy (%)</label>
                        <input type="number" class="form-control @error('pass_accuracy') is-invalid @enderror" id="pass_accuracy" name="pass_accuracy" value="{{ old('pass_accuracy', $stat->pass_accuracy) }}" min="0" max="100" step="0.1">
                        @error('pass_accuracy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="shots" class="form-label">Shots <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('shots') is-invalid @enderror" id="shots" name="shots" value="{{ old('shots', $stat->shots) }}" min="0" required>
                        @error('shots')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="shots_on_target" class="form-label">Shots on Target <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('shots_on_target') is-invalid @enderror" id="shots_on_target" name="shots_on_target" value="{{ old('shots_on_target', $stat->shots_on_target) }}" min="0" required>
                        @error('shots_on_target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="tackles" class="form-label">Tackles <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('tackles') is-invalid @enderror" id="tackles" name="tackles" value="{{ old('tackles', $stat->tackles) }}" min="0" required>
                        @error('tackles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="interceptions" class="form-label">Interceptions <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('interceptions') is-invalid @enderror" id="interceptions" name="interceptions" value="{{ old('interceptions', $stat->interceptions) }}" min="0" required>
                        @error('interceptions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="saves" class="form-label">Saves (Goalkeepers)</label>
                        <input type="number" class="form-control @error('saves') is-invalid @enderror" id="saves" name="saves" value="{{ old('saves', $stat->saves) }}" min="0">
                        @error('saves')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="clean_sheets" class="form-label">Clean Sheets (Goalkeepers)</label>
                        <input type="number" class="form-control @error('clean_sheets') is-invalid @enderror" id="clean_sheets" name="clean_sheets" value="{{ old('clean_sheets', $stat->clean_sheets) }}" min="0">
                        @error('clean_sheets')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('stats.show', $stat->player_id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Statistics</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const playerIdSelect = document.getElementById('player_id');
        const matchIdSelect = document.getElementById('match_id');
        const shotsInput = document.getElementById('shots');
        const shotsOnTargetInput = document.getElementById('shots_on_target');
        
        // Validate shots on target <= shots
        shotsInput.addEventListener('change', validateShots);
        shotsOnTargetInput.addEventListener('change', validateShots);
        
        function validateShots() {
            const shots = parseInt(shotsInput.value) || 0;
            const shotsOnTarget = parseInt(shotsOnTargetInput.value) || 0;
            
            if (shotsOnTarget > shots) {
                shotsOnTargetInput.value = shots;
            }
        }
        
        // Load matches based on selected player
        playerIdSelect.addEventListener('change', function() {
            const playerId = playerIdSelect.value;
            
            if (!playerId) {
                matchIdSelect.innerHTML = '<option value="">Select Match (Optional)</option>';
                return;
            }
            
            // Fetch matches for the selected player's team
            fetch(`/api/players/${playerId}/stats`)
                .then(response => response.json())
                .then(data => {
                    const playerTeamId = data.data.team ? data.data.team.id : null;
                    
                    if (!playerTeamId) {
                        matchIdSelect.innerHTML = '<option value="">Player has no team</option>';
                        return;
                    }
                    
                    // Fetch matches for the player's team
                    return fetch(`/api/teams/${playerTeamId}/matches`);
                })
                .then(response => response.json())
                .then(data => {
                    // Keep the current match if it exists
                    let currentMatchOption = '';
                    if (matchIdSelect.options.length > 0 && matchIdSelect.options[0].value) {
                        currentMatchOption = matchIdSelect.options[0].outerHTML;
                    }
                    
                    matchIdSelect.innerHTML = '<option value="">Select Match (Optional)</option>';
                    
                    // Add current match back if it exists
                    if (currentMatchOption) {
                        matchIdSelect.innerHTML += currentMatchOption;
                    }
                    
                    if (!data || !data.data || !data.data.length) {
                        matchIdSelect.innerHTML += '<option value="">No matches found</option>';
                        return;
                    }
                    
                    // Add matches to the select
                    data.data.forEach(match => {
                        // Skip if this is the current match (already added)
                        if (match.id == matchIdSelect.value) {
                            return;
                        }
                        
                        const matchDate = new Date(match.match_date).toLocaleDateString();
                        const option = document.createElement('option');
                        option.value = match.id;
                        option.textContent = `${match.home_team.name} vs ${match.away_team.name} (${matchDate})`;
                        matchIdSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching matches:', error);
                    matchIdSelect.innerHTML = '<option value="">Error loading matches</option>';
                });
        });
    });
</script>
@endsection