@extends('layouts.app')

@section('title', $player->full_name . ' - Statistics')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('stats.index') }}">Stats</a></li>
                <li class="breadcrumb-item"><a href="{{ route('players.show', $player) }}">{{ $player->full_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Full Statistics</li>
            </ol>
        </nav>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ $player->full_name }} - Statistics</h5>
                <div class="small text-muted">
                    @if($player->team)
                        <a href="{{ route('teams.show', $player->team) }}">{{ $player->team->name }}</a>
                    @else
                        No team
                    @endif
                    | {{ $player->position ? $player->position->name : 'No position' }}
                </div>
            </div>
            <div class="d-flex align-items-center">
                @if($player->photo)
                    <img src="{{ asset('storage/' . $player->photo) }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $player->full_name }}">
                @else
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
                <a href="{{ route('players.show', $player) }}" class="btn btn-outline-primary btn-sm">View Profile</a>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center mb-4">
                <div class="btn-group" role="group" aria-label="Season selector">
                    @foreach($seasons as $season)
                        <a href="{{ route('stats.show', ['id' => $player->id, 'season' => $season]) }}" class="btn btn-outline-primary {{ $currentSeason == $season ? 'active' : '' }}">
                            {{ $season }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Season Summary - {{ $currentSeason }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-2 row-cols-md-3 g-4 text-center">
                                <div class="col">
                                    <div class="card h-100 border-0">
                                        <div class="card-body">
                                            <h3 class="text-primary">{{ $totalStats['matches'] }}</h3>
                                            <p class="text-muted mb-0">Matches</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100 border-0">
                                        <div class="card-body">
                                            <h3 class="text-success">{{ $totalStats['goals'] }}</h3>
                                            <p class="text-muted mb-0">Goals</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100 border-0">
                                        <div class="card-body">
                                            <h3 class="text-info">{{ $totalStats['assists'] }}</h3>
                                            <p class="text-muted mb-0">Assists</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100 border-0">
                                        <div class="card-body">
                                            <h3>{{ $totalStats['minutes_played'] }}</h3>
                                            <p class="text-muted mb-0">Minutes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100 border-0">
                                        <div class="card-body">
                                            <h3 class="text-warning">{{ $totalStats['yellow_cards'] }}</h3>
                                            <p class="text-muted mb-0">Yellow Cards</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100 border-0">
                                        <div class="card-body">
                                            <h3 class="text-danger">{{ $totalStats['red_cards'] }}</h3>
                                            <p class="text-muted mb-0">Red Cards</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Performance Metrics</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="statsRadarChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Detailed Match Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Match</th>
                                    <th>Date</th>
                                    <th>Minutes</th>
                                    <th>Goals</th>
                                    <th>Assists</th>
                                    <th>Passes</th>
                                    <th>Shots</th>
                                    <th>Accuracy</th>
                                    <th>Cards</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($seasonStats->where('match_id', '!=', null) as $stat)
                                    <tr>
                                        <td>
                                            <a href="{{ route('matches.show', $stat->match) }}" class="text-decoration-none">
                                                {{ $stat->match->homeTeam->name }} vs {{ $stat->match->awayTeam->name }}
                                            </a>
                                        </td>
                                        <td>{{ $stat->match->match_date->format('M d, Y') }}</td>
                                        <td>{{ $stat->minutes_played }}'</td>
                                        <td>{{ $stat->goals }}</td>
                                        <td>{{ $stat->assists }}</td>
                                        <td>{{ $stat->passes }}</td>
                                        <td>{{ $stat->shots }}/{{ $stat->shots_on_target }}</td>
                                        <td>{{ round($stat->pass_accuracy, 1) }}%</td>
                                        <td>
                                            @if($stat->yellow_cards > 0)
                                                <span class="badge bg-warning text-dark">{{ $stat->yellow_cards }}Y</span>
                                            @endif
                                            @if($stat->red_cards > 0)
                                                <span class="badge bg-danger">{{ $stat->red_cards }}R</span>
                                            @endif
                                        </td>
                                        <td>
                                            @auth
                                                @if(auth()->user()->isScout())
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('stats.edit', $stat) }}" class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteStatModal{{ $stat->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endauth
                                        </td>
                                    </tr>
                                    
                                    <!-- Delete Stat Modal -->
                                    @auth
                                        @if(auth()->user()->isScout())
                                            <div class="modal fade" id="deleteStatModal{{ $stat->id }}" tabindex="-1" aria-labelledby="deleteStatModalLabel{{ $stat->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteStatModalLabel{{ $stat->id }}">Confirm Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this stat entry for {{ $player->full_name }}?</p>
                                                            <p><strong>Match:</strong> {{ $stat->match->homeTeam->name }} vs {{ $stat->match->awayTeam->name }}</p>
                                                            <p><strong>Date:</strong> {{ $stat->match->match_date->format('M d, Y') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('stats.destroy', $stat) }}" method="POST">
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
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-3">No match statistics available for this season.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @auth
                @if(auth()->user()->isScout())
                    <div class="text-end">
                        <a href="{{ route('stats.create', ['player_id' => $player->id]) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Stat
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create radar chart for player stats
        const statsRadarCtx = document.getElementById('statsRadarChart').getContext('2d');
        
        const statsData = {
            labels: ['Goals', 'Assists', 'Pass Accuracy', 'Shots', 'Tackles', 'Interceptions'],
            datasets: [{
                label: '{{ $currentSeason }}',
                data: [
                    {{ $totalStats['goals'] }},
                    {{ $totalStats['assists'] }},
                    {{ $totalStats['pass_accuracy'] }},
                    {{ $totalStats['shots'] }},
                    {{ $totalStats['tackles'] }},
                    {{ $totalStats['interceptions'] }}
                ],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(54, 162, 235)'
            }]
        };
        
        new Chart(statsRadarCtx, {
            type: 'radar',
            data: statsData,
            options: {
                elements: {
                    line: {
                        borderWidth: 3
                    }
                },
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0
                    }
                }
            }
        });
    });
</script>
@endsection