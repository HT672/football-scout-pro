@extends('layouts.app')

@section('title', 'Home')

@section('content')

<!-- Link to the CSS file -->
<link rel="stylesheet" href="{{ asset('css/HomeStyle.css') }}">

<div class="container">
    <!-- Hero Banner -->
    <div class="bg-primary text-white p-5 mb-4 rounded" style="background: linear-gradient(to top, #007bff, #6f42c1);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4">The Football Scout</h1>
                <p class="lead">Track player performance metrics, compare talents, and discover the next football stars.</p>
                <p>A comprehensive platform for football scouts, coaches, and enthusiasts to analyze player statistics.</p>
                <a href="{{ route('players.index') }}" class="btn btn-light btn-lg mt-3 btn-hover-azure2">Explore Players</a>
            </div>
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/FGLOGO.png') }}" alt="Football Scout" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <!-- Top Players Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h3>Top Performing Players</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($topPlayers as $player)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            @if($player->photo)
                                                <img src="{{ asset('storage/' . $player->photo) }}" class="img-fluid rounded-start" alt="{{ $player->full_name }}">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100 rounded-start">
                                                    <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $player->full_name }}</h5>
                                                <p class="card-text">
                                                    <small class="text-muted">{{ $player->position->name }}</small><br>
                                                    <small class="text-muted">{{ $player->team->name }}</small>
                                                </p>
                                                <p class="card-text">
                                                    <span class="badge bg-success">{{ $player->stats->where('season', '2024-2025')->sum('goals') }} Goals</span>
                                                </p>
                                                <a href="{{ route('players.show', $player) }}" class="btn btn-sm btn-outline-primary btn-hover-azure">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center">No top players data available yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('stats.index') }}" class="btn btn-primary btn-hover-azure2">View All Stats</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Matches and Recent Results -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3>Upcoming Matches</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($upcomingMatches as $match)
                            <a href="{{ route('matches.show', $match) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h5>
                                    <small>{{ $match->match_date->format('M d, Y') }}</small>
                                </div>
                                <p class="mb-1">{{ $match->venue }} | {{ $match->competition }}</p>
                                <small class="text-muted">{{ $match->match_date->format('h:i A') }}</small>
                            </a>
                        @empty
                            <div class="list-group-item">
                                <p class="text-center">No upcoming matches scheduled.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('matches.index') }}" class="btn btn-primary btn-hover-azure2">View All Fixtures</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3>Recent Results</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($recentMatches as $match)
                            <a href="{{ route('matches.show', $match) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h5>
                                    <span class="badge bg-primary">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                </div>
                                <p class="mb-1">{{ $match->venue }} | {{ $match->competition }}</p>
                                <small class="text-muted">{{ $match->match_date->format('M d, Y') }}</small>
                            </a>
                        @empty
                            <div class="list-group-item">
                                <p class="text-center">No recent match results available.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('matches.index') }}" class="btn btn-primary btn-hover-azure2">View All Results</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Teams Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h3>Featured Teams</h3>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-md-4 g-4">
                        @foreach($teams->take(8) as $team)
                            <div class="col">
                                <a href="{{ route('teams.show', $team) }}" class="text-decoration-none">
                                    <div class="card h-100 text-center team-hover">
                                        <div class="p-3">
                                            @if($team->logo)
                                                <img src="{{ asset('storage/' . $team->logo) }}" class="img-fluid" style="max-height: 100px;" alt="{{ $team->name }}">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 100px;">
                                                    <i class="bi bi-shield-fill" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $team->name }}</h5>
                                            <p class="card-text">
                                                <small class="text-muted">{{ $team->league }}</small><br>
                                                <small class="text-muted">{{ $team->country }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('teams.index') }}" class="btn btn-primary btn-hover-azure2">View All Teams</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection