@extends('layouts.app')

@section('title', $team->name)

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teams.index') }}">Teams</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $team->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Team Info Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}" class="img-fluid mb-3" style="max-height: 150px;" alt="{{ $team->name }}">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="height: 150px; width: 150px;">
                            <i class="bi bi-shield-fill" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    
                    <h3>{{ $team->name }}</h3>
                    <div class="badge bg-primary mb-3">{{ $team->league }}</div>
                    
                    @auth
                        @if(auth()->user()->isScout())
                            <div class="btn-group w-100 mb-3">
                                <a href="{{ route('teams.edit', $team) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTeamModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
                
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-globe"></i> Country:</span>
                            <strong>{{ $team->country }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-person"></i> Manager:</span>
                            <strong>{{ $team->manager ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-building"></i> Stadium:</span>
                            <strong>{{ $team->stadium ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-calendar"></i> Founded:</span>
                            <strong>{{ $team->founded ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-people-fill"></i> Players:</span>
                            <strong>{{ $team->players->count() }}</strong>
                        </div>
                    </div>
                </div>
                
                @if($team->description)
                    <div class="card-body">
                        <h5 class="card-title">About</h5>
                        <p class="card-text">{{ $team->description }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Team Content Tabs -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="teamTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="squad-tab" data-bs-toggle="tab" data-bs-target="#squad" type="button" role="tab" aria-controls="squad" aria-selected="true">
                                Squad
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="fixtures-tab" data-bs-toggle="tab" data-bs-target="#fixtures" type="button" role="tab" aria-controls="fixtures" aria-selected="false">
                                Fixtures & Results
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="teamTabContent">
                        <!-- Squad Tab -->
                        <div class="tab-pane fade show active" id="squad" role="tabpanel" aria-labelledby="squad-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Squad List</h5>
                                @auth
                                    @if(auth()->user()->isScout())
                                        <a href="{{ route('players.create', ['team_id' => $team->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus-circle"></i> Add Player
                                        </a>
                                    @endif
                                @endauth
                            </div>

                            @if($team->players->isEmpty())
                                <div class="alert alert-info">
                                    No players in this team. Add players to build the squad.
                                </div>
                            @else
                                <!-- Group players by position -->
                                @php
                                    $playersByPosition = $team->players->groupBy(function($player) {
                                        return $player->position ? $player->position->name : 'Unassigned';
                                    })->sortBy(function($players, $position) {
                                        // Define position order for sorting
                                        $positionOrder = [
                                            'Goalkeeper' => 1,
                                            'Center-Back' => 2,
                                            'Right-Back' => 3,
                                            'Left-Back' => 4,
                                            'Defensive Midfielder' => 5,
                                            'Central Midfielder' => 6,
                                            'Attacking Midfielder' => 7,
                                            'Right Winger' => 8,
                                            'Left Winger' => 9,
                                            'Striker' => 10,
                                            'Unassigned' => 99,
                                        ];
                                        
                                        return $positionOrder[$position] ?? 99;
                                    });
                                @endphp
                                
                                @foreach($playersByPosition as $position => $players)
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2">{{ $position }}</h6>
                                        <div class="row row-cols-1 row-cols-md-2 g-3">
                                            @foreach($players as $player)
                                                <div class="col">
                                                    <div class="card h-100">
                                                        <div class="row g-0">
                                                            <div class="col-3">
                                                                @if($player->photo)
                                                                    <img src="{{ asset('storage/' . $player->photo) }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $player->full_name }}">
                                                                @else
                                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100 rounded-start">
                                                                        <i class="bi bi-person-fill" style="font-size: 2rem;"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-9">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <h6 class="card-title mb-0">
                                                                            <a href="{{ route('players.show', $player) }}" class="text-decoration-none">
                                                                                {{ $player->full_name }}
                                                                            </a>
                                                                        </h6>
                                                                        @if($player->jersey_number)
                                                                            <span class="badge bg-dark">{{ $player->jersey_number }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <p class="card-text small text-muted">
                                                                        {{ $player->nationality }} | {{ $player->age }} years
                                                                    </p>
                                                                    <div class="d-flex justify-content-between align-items-end">
                                                                        <div class="small">
                                                                            @php
                                                                                $seasonStats = $player->stats->where('season', '2024-2025');
                                                                                $goals = $seasonStats->sum('goals');
                                                                                $assists = $seasonStats->sum('assists');
                                                                            @endphp
                                                                            @if($goals > 0 || $assists > 0)
                                                                                <span class="badge bg-success me-1">{{ $goals }} G</span>
                                                                                <span class="badge bg-primary">{{ $assists }} A</span>
                                                                            @else
                                                                                <span class="text-muted">No stats</span>
                                                                            @endif
                                                                        </div>
                                                                        <a href="{{ route('players.show', $player) }}" class="btn btn-sm btn-outline-primary">
                                                                            View
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <!-- Fixtures Tab -->
                        <div class="tab-pane fade" id="fixtures" role="tabpanel" aria-labelledby="fixtures-tab">
                            <div class="row">
                                <!-- Upcoming Matches -->
                                <div class="col-md-6">
                                    <h5 class="card-title">Upcoming Matches</h5>
                                    <div class="list-group mb-4">
                                        @forelse($upcomingMatches as $match)
                                            <a href="{{ route('matches.show', $match) }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="{{ $match->home_team_id == $team->id ? 'fw-bold' : '' }}">
                                                        {{ $match->homeTeam->name }}
                                                    </span>
                                                    <span class="badge bg-primary">vs</span>
                                                    <span class="{{ $match->away_team_id == $team->id ? 'fw-bold' : '' }}">
                                                        {{ $match->awayTeam->name }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2 small text-muted">
                                                    <span><i class="bi bi-calendar"></i> {{ $match->match_date->format('M d, Y') }}</span>
                                                    <span><i class="bi bi-building"></i> {{ $match->venue }}</span>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="list-group-item">
                                                <p class="text-center mb-0">No upcoming matches scheduled.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                
                                <!-- Recent Results -->
                                <div class="col-md-6">
                                    <h5 class="card-title">Recent Results</h5>
                                    <div class="list-group">
                                        @forelse($recentMatches as $match)
                                            <a href="{{ route('matches.show', $match) }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="{{ $match->home_team_id == $team->id ? 'fw-bold' : '' }}">
                                                        {{ $match->homeTeam->name }}
                                                    </span>
                                                    <span class="badge bg-dark">
                                                        {{ $match->home_score }} - {{ $match->away_score }}
                                                    </span>
                                                    <span class="{{ $match->away_team_id == $team->id ? 'fw-bold' : '' }}">
                                                        {{ $match->awayTeam->name }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2 small text-muted">
                                                    <span><i class="bi bi-calendar"></i> {{ $match->match_date->format('M d, Y') }}</span>
                                                    <span><i class="bi bi-trophy"></i> {{ $match->competition }}</span>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="list-group-item">
                                                <p class="text-center mb-0">No recent match results available.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end mt-3">
                                <a href="{{ route('matches.index') }}" class="btn btn-outline-primary">
                                    View All Fixtures
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Team Modal -->
@auth
    @if(auth()->user()->isScout())
        <div class="modal fade" id="deleteTeamModal" tabindex="-1" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTeamModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong>{{ $team->name }}</strong>?</p>
                        <p class="text-danger">This will remove all associations to players but will not delete the players themselves.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('teams.destroy', $team) }}" method="POST">
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
