@extends('layouts.app')

@section('title', $match->homeTeam->name . ' vs ' . $match->awayTeam->name)

@section('content')

<!-- Link to the FixtureStyle CSS file -->
<link rel="stylesheet" href="{{ asset('css/FixtureStyle.css') }}">

<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.index') }}">Matches</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Match Overview -->
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Match Overview</h5>
            @auth
                @if(auth()->user()->isScout())
                    <div class="btn-group">
                        <a href="{{ route('matches.edit', $match) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit Match
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMatchModal">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                @endif
            @endauth
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        @if($match->homeTeam->logo)
                            <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="img-fluid mb-2" style="max-height: 100px;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2 mx-auto" style="height: 100px; width: 100px;">
                                <i class="bi bi-shield-fill" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <h4>{{ $match->homeTeam->name }}</h4>
                        <a href="{{ route('teams.show', $match->homeTeam) }}" class="btn btn-sm btn-outline-primary">View Team</a>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    @if($match->status === 'completed')
                        <div class="bg-dark text-white p-3 rounded mb-3">
                            <h1 class="display-4 mb-0">{{ $match->home_score }} - {{ $match->away_score }}</h1>
                            <div class="small">Full Time</div>
                        </div>
                    @elseif($match->status === 'live')
                        <div class="bg-danger text-white p-3 rounded mb-3">
                            <div class="small mb-2">LIVE</div>
                            <h1 class="display-4 mb-0">{{ $match->home_score }} - {{ $match->away_score }}</h1>
                        </div>
                    @else
                        <div class="mb-3">
                            <h1 class="display-4">VS</h1>
                        </div>
                    @endif
                    
                    <div class="badge bg-primary mb-2">{{ $match->match_date->format('l, M d, Y') }}</div>
                    <p class="mb-1">{{ $match->match_date->format('h:i A') }}</p>
                    
                    <div class="d-flex flex-column align-items-center mt-3">
                        <div class="badge bg-secondary mb-2">{{ $match->competition }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt"></i> {{ $match->venue }}
                        </div>
                        @if($match->attendance)
                            <div class="text-muted small mt-1">
                                <i class="bi bi-people"></i> Attendance: {{ number_format($match->attendance) }}
                            </div>
                        @endif
                    </div>
                    
                    @auth
                        @if(auth()->user()->isScout() && ($match->status === 'scheduled' || $match->status === 'live'))
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#liveUpdateModal">
                                    <i class="bi bi-lightning"></i> Update Score
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        @if($match->awayTeam->logo)
                            <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="img-fluid mb-2" style="max-height: 100px;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2 mx-auto" style="height: 100px; width: 100px;">
                                <i class="bi bi-shield-fill" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <h4>{{ $match->awayTeam->name }}</h4>
                        <a href="{{ route('teams.show', $match->awayTeam) }}" class="btn btn-sm btn-outline-primary">View Team</a>
                    </div>
                </div>
            </div>
            
            @if($match->match_summary)
                <div class="mt-4">
                    <h5>Match Summary</h5>
                    <p>{{ $match->match_summary }}</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Match Events -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Match Events</h5>
                    @auth
                        @if(auth()->user()->isScout() && ($match->status === 'live' || $match->status === 'completed'))
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="bi bi-plus-circle"></i> Add Event
                            </button>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                    @if($match->events->isEmpty())
                        <div class="alert alert-info">
                            <p class="text-center mb-0">No events recorded for this match yet.</p>
                        </div>
                    @else
                        <div class="position-relative">
                            <div class="position-absolute h-100" style="left: 50%; width: 2px; background-color: #e0e0e0;"></div>
                            
                            @foreach($match->events->sortBy('minute') as $event)
                                <div class="row mb-3">
                                    @if($event->player->team_id == $match->home_team_id)
                                        <div class="col-5 text-end">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="me-2">
                                                    <strong>{{ $event->player->full_name }}</strong>
                                                    <div class="small text-muted">{{ $event->description }}</div>
                                                </div>
                                                <div>
                                                    @switch($event->event_type)
                                                        @case('goal')
                                                            <i class="bi bi-trophy text-success fs-4"></i>
                                                            @break
                                                        @case('assist')
                                                            <i class="bi bi-check-circle text-primary fs-4"></i>
                                                            @break
                                                        @case('yellow_card')
                                                            <div class="bg-warning" style="width: 20px; height: 28px;"></div>
                                                            @break
                                                        @case('red_card')
                                                            <div class="bg-danger" style="width: 20px; height: 28px;"></div>
                                                            @break
                                                        @case('substitution_in')
                                                            <i class="bi bi-arrow-down-circle text-success fs-4"></i>
                                                            @break
                                                        @case('substitution_out')
                                                            <i class="bi bi-arrow-up-circle text-danger fs-4"></i>
                                                            @break
                                                        @case('penalty_missed')
                                                            <i class="bi bi-x-circle text-danger fs-4"></i>
                                                            @break
                                                        @case('penalty_saved')
                                                            <i class="bi bi-hand-thumbs-up text-primary fs-4"></i>
                                                            @break
                                                        @case('own_goal')
                                                            <i class="bi bi-arrow-left-right text-danger fs-4"></i>
                                                            @break
                                                        @default
                                                            <i class="bi bi-circle text-secondary fs-4"></i>
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-center">
                                            <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; z-index: 1; position: relative;">
                                                {{ $event->minute }}'
                                            </div>
                                        </div>
                                        <div class="col-5"></div>
                                    @else
                                        <div class="col-5"></div>
                                        <div class="col-2 text-center">
                                            <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; z-index: 1; position: relative;">
                                                {{ $event->minute }}'
                                            </div>
                                        </div>
                                        <div class="col-5 text-start">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    @switch($event->event_type)
                                                        @case('goal')
                                                            <i class="bi bi-trophy text-success fs-4"></i>
                                                            @break
                                                        @case('assist')
                                                            <i class="bi bi-check-circle text-primary fs-4"></i>
                                                            @break
                                                        @case('yellow_card')
                                                            <div class="bg-warning" style="width: 20px; height: 28px;"></div>
                                                            @break
                                                        @case('red_card')
                                                            <div class="bg-danger" style="width: 20px; height: 28px;"></div>
                                                            @break
                                                        @case('substitution_in')
                                                            <i class="bi bi-arrow-down-circle text-success fs-4"></i>
                                                            @break
                                                        @case('substitution_out')
                                                            <i class="bi bi-arrow-up-circle text-danger fs-4"></i>
                                                            @break
                                                        @case('penalty_missed')
                                                            <i class="bi bi-x-circle text-danger fs-4"></i>
                                                            @break
                                                        @case('penalty_saved')
                                                            <i class="bi bi-hand-thumbs-up text-primary fs-4"></i>
                                                            @break
                                                        @case('own_goal')
                                                            <i class="bi bi-arrow-left-right text-danger fs-4"></i>
                                                            @break
                                                        @default
                                                            <i class="bi bi-circle text-secondary fs-4"></i>
                                                    @endswitch
                                                </div>
                                                <div class="ms-2">
                                                    <strong>{{ $event->player->full_name }}</strong>
                                                    <div class="small text-muted">{{ $event->description }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Team Lineups</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="lineupTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="pill" data-bs-target="#home-lineup" type="button" role="tab" aria-controls="home-lineup" aria-selected="true">
                                {{ $match->homeTeam->name }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="away-tab" data-bs-toggle="pill" data-bs-target="#away-lineup" type="button" role="tab" aria-controls="away-lineup" aria-selected="false">
                                {{ $match->awayTeam->name }}
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="lineupTabContent">
                        <!-- Home Team Lineup -->
                        <div class="tab-pane fade show active" id="home-lineup" role="tabpanel" aria-labelledby="home-tab">
                            @if($homeTeamPlayers->isEmpty())
                                <div class="alert alert-info">
                                    <p class="text-center mb-0">No players available for this team.</p>
                 <div class="alert alert-info">
                                    <p class="text-center mb-0">No players available for this team.</p>
                                </div>
                            @else
                                <div class="list-group">
                                    @foreach($homeTeamPlayers->groupBy('position.name') as $position => $players)
                                        <div class="mb-2">
                                            <h6 class="small text-muted">{{ $position ?? 'Unassigned' }}</h6>
                                            @foreach($players as $player)
                                                <a href="{{ route('players.show', $player) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div>
                                                        @if($player->jersey_number)
                                                            <span class="badge bg-dark me-2">{{ $player->jersey_number }}</span>
                                                        @endif
                                                        {{ $player->full_name }}
                                                    </div>
                                                    @php
                                                        $playerEvents = $match->events->where('player_id', $player->id);
                                                        $goals = $playerEvents->where('event_type', 'goal')->count();
                                                        $assists = $playerEvents->where('event_type', 'assist')->count();
                                                        $yellowCards = $playerEvents->where('event_type', 'yellow_card')->count();
                                                        $redCards = $playerEvents->where('event_type', 'red_card')->count();
                                                    @endphp
                                                    
                                                    <div>
                                                        @if($goals > 0)
                                                            <span class="badge bg-success">{{ $goals }} G</span>
                                                        @endif
                                                        
                                                        @if($assists > 0)
                                                            <span class="badge bg-primary">{{ $assists }} A</span>
                                                        @endif
                                                        
                                                        @if($yellowCards > 0)
                                                            <span class="badge bg-warning text-dark">YC</span>
                                                        @endif
                                                        
                                                        @if($redCards > 0)
                                                            <span class="badge bg-danger">RC</span>
                                                        @endif
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <!-- Away Team Lineup -->
                        <div class="tab-pane fade" id="away-lineup" role="tabpanel" aria-labelledby="away-tab">
                            @if($awayTeamPlayers->isEmpty())
                                <div class="alert alert-info">
                                    <p class="text-center mb-0">No players available for this team.</p>
                                </div>
                            @else
                                <div class="list-group">
                                    @foreach($awayTeamPlayers->groupBy('position.name') as $position => $players)
                                        <div class="mb-2">
                                            <h6 class="small text-muted">{{ $position ?? 'Unassigned' }}</h6>
                                            @foreach($players as $player)
                                                <a href="{{ route('players.show', $player) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div>
                                                        @if($player->jersey_number)
                                                            <span class="badge bg-dark me-2">{{ $player->jersey_number }}</span>
                                                        @endif
                                                        {{ $player->full_name }}
                                                    </div>
                                                    @php
                                                        $playerEvents = $match->events->where('player_id', $player->id);
                                                        $goals = $playerEvents->where('event_type', 'goal')->count();
                                                        $assists = $playerEvents->where('event_type', 'assist')->count();
                                                        $yellowCards = $playerEvents->where('event_type', 'yellow_card')->count();
                                                        $redCards = $playerEvents->where('event_type', 'red_card')->count();
                                                    @endphp
                                                    
                                                    <div>
                                                        @if($goals > 0)
                                                            <span class="badge bg-success">{{ $goals }} G</span>
                                                        @endif
                                                        
                                                        @if($assists > 0)
                                                            <span class="badge bg-primary">{{ $assists }} A</span>
                                                        @endif
                                                        
                                                        @if($yellowCards > 0)
                                                            <span class="badge bg-warning text-dark">YC</span>
                                                        @endif
                                                        
                                                        @if($redCards > 0)
                                                            <span class="badge bg-danger">RC</span>
                                                        @endif
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Update Modal -->
@auth
    @if(auth()->user()->isScout() && ($match->status === 'scheduled' || $match->status === 'live'))
        <div class="modal fade" id="liveUpdateModal" tabindex="-1" aria-labelledby="liveUpdateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="liveUpdateModalLabel">Update Match Score</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="liveUpdateForm">
                            <div class="row mb-3 align-items-center">
                                <div class="col-5 text-center">
                                    <label class="form-label">{{ $match->homeTeam->name }}</label>
                                    <input type="number" class="form-control text-center" id="home_score" name="home_score" value="{{ $match->home_score }}" min="0">
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fs-4">-</span>
                                </div>
                                <div class="col-5 text-center">
                                    <label class="form-label">{{ $match->awayTeam->name }}</label>
                                    <input type="number" class="form-control text-center" id="away_score" name="away_score" value="{{ $match->away_score }}" min="0">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Match Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="live" {{ $match->status === 'live' ? 'selected' : '' }}>Live</option>
                                    <option value="completed" {{ $match->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="updateScoreBtn">Update Score</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Add Event Modal -->
@auth
    @if(auth()->user()->isScout() && ($match->status === 'live' || $match->status === 'completed'))
        <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventModalLabel">Add Match Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('matches.events.store', $match) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="player_id" class="form-label">Player</label>
                                <select class="form-select" id="player_id" name="player_id" required>
                                    <optgroup label="{{ $match->homeTeam->name }}">
                                        @foreach($homeTeamPlayers as $player)
                                            <option value="{{ $player->id }}">{{ $player->full_name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{ $match->awayTeam->name }}">
                                        @foreach($awayTeamPlayers as $player)
                                            <option value="{{ $player->id }}">{{ $player->full_name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="event_type" class="form-label">Event Type</label>
                                <select class="form-select" id="event_type" name="event_type" required>
                                    <option value="goal">Goal</option>
                                    <option value="assist">Assist</option>
                                    <option value="yellow_card">Yellow Card</option>
                                    <option value="red_card">Red Card</option>
                                    <option value="substitution_in">Substitution (In)</option>
                                    <option value="substitution_out">Substitution (Out)</option>
                                    <option value="penalty_missed">Penalty Missed</option>
                                    <option value="penalty_saved">Penalty Saved</option>
                                    <option value="own_goal">Own Goal</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="minute" class="form-label">Minute</label>
                                <input type="number" class="form-control" id="minute" name="minute" min="1" max="120" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Delete Match Modal -->
@auth
    @if(auth()->user()->isScout())
        <div class="modal fade" id="deleteMatchModal" tabindex="-1" aria-labelledby="deleteMatchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMatchModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this match?</p>
                        <p><strong>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</strong></p>
                        <p><strong>Date:</strong> {{ $match->match_date->format('M d, Y h:i A') }}</p>
                        @if($match->status === 'completed')
                            <p><strong>Result:</strong> {{ $match->home_score }} - {{ $match->away_score }}</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('matches.destroy', $match) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection

@section('scripts')
@auth
    @if(auth()->user()->isScout() && ($match->status === 'scheduled' || $match->status === 'live'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const updateScoreBtn = document.getElementById('updateScoreBtn');
                const liveUpdateForm = document.getElementById('liveUpdateForm');
                const liveUpdateModal = document.getElementById('liveUpdateModal');
                
                updateScoreBtn.addEventListener('click', function() {
                    const formData = new FormData(liveUpdateForm);
                    const data = Object.fromEntries(formData.entries());
                    
                    // Send AJAX request to update score
                    const updateUrl = "{{ route('matches.live-update', $match->id) }}";
                    fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show updated score
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the score. Please try again.');
                    });
                });
            });
        </script>
    @endif
@endauth
@endsection