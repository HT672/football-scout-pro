@extends('layouts.dashboard')

@section('dashboard-title', 'Dashboard')

@section('dashboard-content')
<div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
    <div class="col">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Players</h6>
                        <h2 class="card-text">{{ $totalPlayers }}</h2>
                    </div>
                    <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="{{ route('players.index') }}" class="text-white">View all players <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Teams</h6>
                        <h2 class="card-text">{{ $totalTeams }}</h2>
                    </div>
                    <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="{{ route('teams.index') }}" class="text-white">View all teams <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Upcoming Matches</h6>
                        <h2 class="card-text">{{ $upcomingMatches->count() }}</h2>
                    </div>
                    <i class="bi bi-calendar-event-fill" style="font-size: 3rem;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="{{ route('matches.index') }}" class="text-white">View fixtures <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Your Role</h6>
                        <h2 class="card-text">{{ ucfirst(auth()->user()->role) }}</h2>
                    </div>
                    <i class="bi bi-person-badge-fill" style="font-size: 3rem;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <span class="text-white">
                    @if(auth()->user()->isScout())
                        Scout access granted
                    @else
                        Limited permissions
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Players by Position</h5>
            </div>
            <div class="card-body">
                <canvas id="playersByPositionChart" width="100%" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Upcoming Matches</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($upcomingMatches as $match)
                        <a href="{{ route('matches.show', $match) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h6>
                                <small>{{ $match->match_date->format('M d, Y') }}</small>
                            </div>
                            <p class="mb-1">{{ $match->venue }} | {{ $match->competition }}</p>
                            <small>{{ $match->match_date->format('h:i A') }}</small>
                        </a>
                    @empty
                        <div class="list-group-item">
                            <p class="text-center">No upcoming matches scheduled.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('matches.index') }}" class="btn btn-primary btn-sm">View All Fixtures</a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('players.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-person-plus-fill fs-4 d-block mb-2"></i>
                            Add New Player
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teams.create') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-shield-plus fs-4 d-block mb-2"></i>
                            Add New Team
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('matches.create') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-calendar-plus fs-4 d-block mb-2"></i>
                            Schedule Match
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('stats.create') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-graph-up-arrow fs-4 d-block mb-2"></i>
                            Record Stats
                        </a>
                    </div>
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
    // Players by Position Chart
    var chartCanvas = document.getElementById('playersByPositionChart');
    
    if (chartCanvas) {
        var ctx = chartCanvas.getContext('2d');
        
        // Define chart data using Blade-rendered variables
        var positionLabels = JSON.parse('@json($playersByPosition->keys())');
        var positionValues = JSON.parse('@json($playersByPosition->values())');
        
        var chartData = {
            labels: positionLabels,
            datasets: [{
                label: 'Number of Players',
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(199, 199, 199, 0.6)',
                    'rgba(83, 102, 255, 0.6)',
                    'rgba(40, 159, 64, 0.6)',
                    'rgba(210, 199, 199, 0.6)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(199, 199, 199, 1)',
                    'rgba(83, 102, 255, 1)',
                    'rgba(40, 159, 64, 1)',
                    'rgba(210, 199, 199, 1)',
                ],
                borderWidth: 1,
                data: positionValues,
            }]
        };
        
        new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Players Distribution by Position'
                    }
                }
            }
        });
    }
});
</script>
@endsection