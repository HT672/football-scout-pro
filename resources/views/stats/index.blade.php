@extends('layouts.app')

@section('title', 'Statistics')

@section('content')

<!-- Link to the StatStyle CSS file -->
<link rel="stylesheet" href="{{ asset('css/StatStyle.css') }}">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Player Statistics</h1>
        @auth
            @if(auth()->user()->isScout())
                <a href="{{ route('stats.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Stats
                </a>
            @endif
        @endauth
    </div>

    <!-- Season Selector -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="Season selector">
                    @foreach($seasons as $season)
                        <a href="{{ route('stats.index', ['season' => $season]) }}" class="btn btn-outline-primary {{ $currentSeason == $season ? 'active' : '' }}">
                            {{ $season }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Scorers -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Top Goal Scorers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Player</th>
                                    <th>Team</th>
                                    <th class="text-end">Goals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topScorers as $index => $player)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('players.show', $player) }}" class="text-decoration-none">
                                                {{ $player->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($player->team)
                                                <a href="{{ route('teams.show', $player->team) }}" class="text-decoration-none">
                                                    {{ $player->team->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">No team</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-success">{{ $player->stats->sum('goals') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No data available for this season.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Assists -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Top Assists</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Player</th>
                                    <th>Team</th>
                                    <th class="text-end">Assists</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topAssists as $index => $player)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('players.show', $player) }}" class="text-decoration-none">
                                                {{ $player->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($player->team)
                                                <a href="{{ route('teams.show', $player->team) }}" class="text-decoration-none">
                                                    {{ $player->team->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">No team</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-primary">{{ $player->stats->sum('assists') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No data available for this season.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Charts -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Statistics Visualization</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h6 class="text-center mb-3">Top 5 Goal Scorers</h6>
                    <canvas id="goalScorersChart" height="250"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <h6 class="text-center mb-3">Top 5 Assisters</h6>
                    <canvas id="assistsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart for Goal Scorers
        const goalScorersCtx = document.getElementById('goalScorersChart').getContext('2d');
        const goalScorersData = {
            labels: {!! json_encode($topScorers->take(5)->pluck('full_name')) !!},
            datasets: [{
                label: 'Goals',
                data: {!! json_encode($topScorers->take(5)->map(function($player) { return $player->stats->sum('goals'); })) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        };
        
        new Chart(goalScorersCtx, {
            type: 'bar',
            data: goalScorersData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        
        // Chart for Assists
        const assistsCtx = document.getElementById('assistsChart').getContext('2d');
        const assistsData = {
            labels: {!! json_encode($topAssists->take(5)->pluck('full_name')) !!},
            datasets: [{
                label: 'Assists',
                data: {!! json_encode($topAssists->take(5)->map(function($player) { return $player->stats->sum('assists'); })) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        };
        
        new Chart(assistsCtx, {
            type: 'bar',
            data: assistsData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection