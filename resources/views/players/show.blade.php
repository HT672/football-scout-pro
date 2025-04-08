@extends('layouts.app')

@section('title', $player->full_name)

@section('content')

<!-- Link to the LayoutStyle CSS file -->
<link rel="stylesheet" href="{{ asset('css/PlayerStyle.css') }}">

<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item crumbss"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item crumbss"><a href="{{ route('players.index') }}">Players</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $player->full_name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Player Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($player->photo)
                        <img src="{{ asset('storage/' . $player->photo) }}" class="rounded-circle img-thumbnail mb-3" style="width: 180px; height: 180px; object-fit: cover;" alt="{{ $player->full_name }}">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 180px; height: 180px;">
                            <i class="bi bi-person-fill" style="font-size: 6rem;"></i>
                        </div>
                    @endif
                    
                    <h3>{{ $player->full_name }}</h3>
                    
                    @if($player->team)
                        <p class="mb-1">
                            <a href="{{ route('teams.show', $player->team) }}" class="text-decoration-none">
                                <span class="badge bg-primary">{{ $player->team->name }}</span>
                            </a>
                        </p>
                    @endif
                    
                    @if($player->position)
                        <p class="mb-3">
                            <span class="badge bg-secondary">{{ $player->position->name }}</span>
                            @if($player->jersey_number)
                                <span class="badge bg-dark">#{{ $player->jersey_number }}</span>
                            @endif
                        </p>
                    @endif
                    
                    @auth
                        @if(auth()->user()->isScout())
                            <div class="btn-group w-100 mb-3">
                                <a href="{{ route('players.edit', $player) }}" class="btn btn-outline-primary btn-hover-azure">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePlayerModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
                
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-calendar"></i> Age:</span>
                            <strong>{{ $player->age }} years</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-globe"></i> Nationality:</span>
                            <strong>{{ $player->nationality }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-rulers"></i> Height:</span>
                            <strong>{{ $player->height ? $player->height . ' cm' : 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-lightning"></i> Weight:</span>
                            <strong>{{ $player->weight ? $player->weight . ' kg' : 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-dribbble"></i> Preferred Foot:</span>
                            <strong>{{ ucfirst($player->preferred_foot) }}</strong>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-cash"></i> Market Value:</span>
                            <strong>{{ $player->market_value ? '€' . $player->market_value . 'M' : 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
                
                @if($player->bio)
                    <div class="card-body">
                        <h5 class="card-title">Biography</h5>
                        <p class="card-text">{{ $player->bio }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Player Stats -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="playerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="true">
                                Career Stats
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chart" type="button" role="tab" aria-controls="chart" aria-selected="false">
                                Performance Chart
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                                Match History
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="playerTabContent">
                        <!-- Stats Tab -->
                        <div class="tab-pane fade show active" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                            <h5 class="card-title">Career Statistics</h5>
                            
                            @if($seasonStats->isEmpty())
                                <div class="alert alert-info">
                                    No statistics available for this player.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Season</th>
                                                <th>Matches</th>
                                                <th>Goals</th>
                                                <th>Assists</th>
                                                <th>Minutes</th>
                                                <th>Yellow Cards</th>
                                                <th>Red Cards</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($seasonStats as $season => $stats)
                                                <tr>
                                                    <td>{{ $season }}</td>
                                                    <td>{{ $stats['matches'] }}</td>
                                                    <td>{{ $stats['goals'] }}</td>
                                                    <td>{{ $stats['assists'] }}</td>
                                                    <td>{{ $stats['minutes_played'] }}</td>
                                                    <td>{{ $stats['yellow_cards'] }}</td>
                                                    <td>{{ $stats['red_cards'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-dark">
                                                <td><strong>Total</strong></td>
                                                <td>{{ $seasonStats->sum('matches') }}</td>
                                                <td>{{ $seasonStats->sum('goals') }}</td>
                                                <td>{{ $seasonStats->sum('assists') }}</td>
                                                <td>{{ $seasonStats->sum('minutes_played') }}</td>
                                                <td>{{ $seasonStats->sum('yellow_cards') }}</td>
                                                <td>{{ $seasonStats->sum('red_cards') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                @auth
                                    @if(auth()->user()->isScout())
                                        <div class="text-end">
                                            <a href="{{ route('stats.create', ['player_id' => $player->id]) }}" class="btn btn-primary btn-sm btn-hover-azure">
                                                <i class="bi bi-plus-circle"></i> Add Stats
                                            </a>
                                        </div>
                                    @endif
                                @endauth
                            @endif
                        </div>
                        
                        <!-- Performance Chart Tab -->
                        <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab">
                            <h5 class="card-title">Performance Metrics</h5>
                            
                            @if($seasonStats->isEmpty())
                                <div class="alert alert-info">
                                    No statistics available for this player.
                                </div>
                            @else
                                <div style="height: 400px;">
                                    <canvas id="performanceChart"></canvas>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Match History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <h5 class="card-title">Recent Matches</h5>
                            
                            @if($player->stats->isEmpty())
                                <div class="alert alert-info">
                                    No match data available for this player.
                                </div>
                            @else
                                <div class="list-group">
                                    @foreach($player->stats->take(5) as $stat)
                                        @if($stat->match)
                                            <a href="{{ route('matches.show', $stat->match) }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">{{ $stat->match->homeTeam->name }} {{ $stat->match->home_score }} - {{ $stat->match->away_score }} {{ $stat->match->awayTeam->name }}</h6>
                                                    <small>{{ $stat->match->match_date->format('M d, Y') }}</small>
                                                </div>
                                                <p class="mb-1">
                                                    <span class="badge bg-success">{{ $stat->goals }} Goals</span>
                                                    <span class="badge bg-primary">{{ $stat->assists }} Assists</span>
                                                    <span class="badge bg-info">{{ $stat->minutes_played }}' Played</span>
                                                    @if($stat->yellow_cards > 0)
                                                        <span class="badge bg-warning text-dark">{{ $stat->yellow_cards }} YC</span>
                                                    @endif
                                                    @if($stat->red_cards > 0)
                                                        <span class="badge bg-danger">{{ $stat->red_cards }} RC</span>
                                                    @endif
                                                </p>
                                                <small>{{ $stat->match->competition }}</small>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                
                                <!--------- DANGER ZONE NOT WORKING -------->
                                <div class="text-end mt-3">
                                    <a href="{{ route('stats.show', $player->id) }}" class="btn btn-outline-primary btn-sm btn-hover-azure">
                                        View Full Match History
                                    </a>
                                </div>
                                <!------------------------------------------>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Player Modal -->
@auth
    @if(auth()->user()->isScout())
        <div class="modal fade" id="deletePlayerModal" tabindex="-1" aria-labelledby="deletePlayerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletePlayerModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong>{{ $player->full_name }}</strong>? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-hover-azure" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('players.destroy', $player) }}" method="POST">
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
@if(!$seasonStats->isEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        const seasons = {!! json_encode($seasonStats->keys()) !!};
        const goals = {!! json_encode($seasonStats->pluck('goals')) !!};
        const assists = {!! json_encode($seasonStats->pluck('assists')) !!};
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: 'Goals',
                        data: goals,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Assists',
                        data: assists,
                        backgroundColor: 'rgba(255, 159, 64, 0.7)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
