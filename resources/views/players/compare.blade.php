@extends('layouts.app')

@section('title', 'Compare Players')

@section('content')
<div class="container">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('players.index') }}">Players</a></li>
                <li class="breadcrumb-item active" aria-current="page">Compare Players</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Player Comparison</h5>
        </div>
        <div class="card-body">
            @if(count($players) < 2)
                <div class="alert alert-warning">
                    Please select at least 2 players to compare.
                </div>
                <div class="text-center">
                    <a href="{{ route('players.index') }}" class="btn btn-primary">Return to Players List</a>
                </div>
            @else
                <div class="d-flex justify-content-center mb-4">
                    <div class="btn-group">
                        @foreach($seasons as $season)
                            <a href="{{ route('players.compare', ['players' => $players->pluck('id'), 'season' => $season]) }}" class="btn btn-outline-primary {{ request('season', $seasons[0]) == $season ? 'active' : '' }}">
                                {{ $season }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Attributes</th>
                                @foreach($players as $player)
                                    <th class="text-center">
                                        <div class="mb-2">
                                            @if($player->photo)
                                                <img src="{{ asset('storage/' . $player->photo) }}" class="rounded-circle" width="50" height="50" alt="{{ $player->full_name }}">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('players.show', $player) }}">{{ $player->full_name }}</a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Team</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        @if($player->team)
                                            <a href="{{ route('teams.show', $player->team) }}">{{ $player->team->name }}</a>
                                        @else
                                            <span class="text-muted">No team</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Position</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ optional($player->position)->name ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Age</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ $player->age }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Nationality</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ $player->nationality }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Height</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ $player->height ? $player->height . ' cm' : 'N/A' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Weight</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ $player->weight ? $player->weight . ' kg' : 'N/A' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Preferred Foot</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ ucfirst($player->preferred_foot) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Market Value</td>
                                @foreach($players as $player)
                                    <td class="text-center">{{ $player->market_value ? '€' . $player->market_value . 'M' : 'N/A' }}</td>
                                @endforeach
                            </tr>
                            
                            <tr class="table-primary">
                                <th colspan="{{ count($players) + 1 }}" class="text-center">Season {{ request('season', $seasons[0]) }} Stats</th>
                            </tr>
                            
                            <tr>
                                <td>Matches Played</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->count() }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Goals</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('goals') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Assists</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('assists') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Minutes Played</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('minutes_played') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Yellow Cards</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('yellow_cards') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Red Cards</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('red_cards') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Passes</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('passes') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Pass Accuracy</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ number_format($player->stats->where('season', request('season', $seasons[0]))->avg('pass_accuracy'), 1) }}%
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Shots</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('shots') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Shots on Target</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('shots_on_target') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Shot Accuracy</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        @php
                                            $shots = $player->stats->where('season', request('season', $seasons[0]))->sum('shots');
                                            $shotsOnTarget = $player->stats->where('season', request('season', $seasons[0]))->sum('shots_on_target');
                                            $shotAccuracy = $shots > 0 ? ($shotsOnTarget / $shots) * 100 : 0;
                                        @endphp
                                        {{ number_format($shotAccuracy, 1) }}%
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Tackles</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('tackles') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Interceptions</td>
                                @foreach($players as $player)
                                    <td class="text-center">
                                        {{ $player->stats->where('season', request('season', $seasons[0]))->sum('interceptions') }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <canvas id="statsRadarChart"></canvas>
                </div>
            @endif
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('players.index') }}" class="btn btn-primary">Back to Players List</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(count($players) >= 2)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statsRadarChart').getContext('2d');
        
        // Prepare data for radar chart
        const playerNames = {!! json_encode($players->pluck('full_name')) !!};
        const currentSeason = '{!! request('season', $seasons[0]) !!}';
        
        // Calculate max values for each stat to normalize the data
        const maxGoals = Math.max(...{!! json_encode($players->map(function($player) use($seasons) { 
            return $player->stats->where('season', request('season', $seasons[0]))->sum('goals');
        })) !!});
        
        const maxAssists = Math.max(...{!! json_encode($players->map(function($player) use($seasons) { 
            return $player->stats->where('season', request('season', $seasons[0]))->sum('assists');
        })) !!});
        
        const maxPasses = Math.max(...{!! json_encode($players->map(function($player) use($seasons) { 
            return $player->stats->where('season', request('season', $seasons[0]))->sum('passes');
        })) !!});
        
        const maxShots = Math.max(...{!! json_encode($players->map(function($player) use($seasons) { 
            return $player->stats->where('season', request('season', $seasons[0]))->sum('shots');
        })) !!});
        
        const maxTackles = Math.max(...{!! json_encode($players->map(function($player) use($seasons) { 
            return $player->stats->where('season', request('season', $seasons[0]))->sum('tackles');
        })) !!});
        
        const maxInterceptions = Math.max(...{!! json_encode($players->map(function($player) use($seasons) { 
            return $player->stats->where('season', request('season', $seasons[0]))->sum('interceptions');
        })) !!});
        
        // Prepare datasets
        const datasets = [];
        const colors = ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)'];
        
        @foreach($players as $index => $player)
        datasets.push({
            label: '{{ $player->full_name }}',
            data: [
                {{ $player->stats->where('season', request('season', $seasons[0]))->sum('goals') }} / (maxGoals || 1) * 100,
                {{ $player->stats->where('season', request('season', $seasons[0]))->sum('assists') }} / (maxAssists || 1) * 100,
                {{ $player->stats->where('season', request('season', $seasons[0]))->sum('passes') }} / (maxPasses || 1) * 100,
                {{ $player->stats->where('season', request('season', $seasons[0]))->avg('pass_accuracy') ?? 0 }},
                {{ $player->stats->where('season', request('season', $seasons[0]))->sum('shots') }} / (maxShots || 1) * 100,
                {{ $player->stats->where('season', request('season', $seasons[0]))->sum('tackles') }} / (maxTackles || 1) * 100,
                {{ $player->stats->where('season', request('season', $seasons[0]))->sum('interceptions') }} / (maxInterceptions || 1) * 100,
            ],
            backgroundColor: '{{ $colors[$index % count($colors)] }}',
            borderColor: '{{ str_replace('0.7', '1', $colors[$index % count($colors)]) }}',
            borderWidth: 2,
            pointBackgroundColor: '{{ str_replace('0.7', '1', $colors[$index % count($colors)]) }}',
        });
        @endforeach
        
        // Create radar chart
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Goals', 'Assists', 'Passes', 'Pass Accuracy (%)', 'Shots', 'Tackles', 'Interceptions'],
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Player Comparison - Season ' + currentSeason,
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    // For Pass Accuracy, show the actual percentage
                                    if (context.label === 'Pass Accuracy (%)') {
                                        label += context.raw.toFixed(1) + '%';
                                    } else {
                                        // For normalized values, show the original value
                                        const originalValues = [
                                            maxGoals > 0 ? Math.round(context.raw * maxGoals / 100) : 0,
                                            maxAssists > 0 ? Math.round(context.raw * maxAssists / 100) : 0,
                                            maxPasses > 0 ? Math.round(context.raw * maxPasses / 100) : 0,
                                            context.raw.toFixed(1) + '%', // Already handled above
                                            maxShots > 0 ? Math.round(context.raw * maxShots / 100) : 0,
                                            maxTackles > 0 ? Math.round(context.raw * maxTackles / 100) : 0,
                                            maxInterceptions > 0 ? Math.round(context.raw * maxInterceptions / 100) : 0,
                                        ];
                                        
                                        label += originalValues[context.dataIndex];
                                    }
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection