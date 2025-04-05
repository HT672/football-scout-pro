@extends('layouts.app')

@section('title', 'Fixtures & Results')

@section('content')

<!-- Link to the FixtureStyle CSS file -->
<link rel="stylesheet" href="{{ asset('css/FixtureStyle.css') }}">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Fixtures & Results</h1>
        @auth
            @if(auth()->user()->isScout())
                <a href="{{ route('matches.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Match
                </a>
            @endif
        @endauth
    </div>

    <ul class="nav nav-tabs mb-4" id="matchTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active btn-hover-azure" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                Upcoming Matches
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link btn-hover-azure" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab" aria-controls="recent" aria-selected="false">
                Recent Results
            </button>
        </li>
    </ul>
    
    <div class="tab-content" id="matchTabContent">
        <!-- Upcoming Matches Tab -->
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Upcoming Fixtures</h5>
                </div>
                <div class="card-body">
                    @forelse($upcomingMatches as $match)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-md-end">
                                        <div class="d-flex flex-column align-items-center align-items-md-end">
                                            @if($match->homeTeam->logo)
                                                <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="img-fluid mb-2" style="max-height: 60px;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2" style="height: 60px; width: 60px;">
                                                    <i class="bi bi-shield-fill"></i>
                                                </div>
                                            @endif
                                            <h6>{{ $match->homeTeam->name }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="badge bg-primary mb-2">{{ $match->match_date->format('l, M d, Y') }}</div>
                                            <h3 class="mb-2">VS</h3>
                                            <div>{{ $match->match_date->format('h:i A') }}</div>
                                            <div class="text-muted small mt-2">
                                                <i class="bi bi-geo-alt"></i> {{ $match->venue }}
                                            </div>
                                            <div class="badge bg-secondary mt-2">{{ $match->competition }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-md-start">
                                        <div class="d-flex flex-column align-items-center align-items-md-start">
                                            @if($match->awayTeam->logo)
                                                <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="img-fluid mb-2" style="max-height: 60px;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2" style="height: 60px; width: 60px;">
                                                    <i class="bi bi-shield-fill"></i>
                                                </div>
                                            @endif
                                            <h6>{{ $match->awayTeam->name }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('matches.show', $match) }}" class="btn btn-outline-primary btn-sm btn-hover-azure">
                                        <i class="bi bi-info-circle"></i> Match Details
                                    </a>
                                    @auth
                                        @if(auth()->user()->isScout())
                                            <a href="{{ route('matches.edit', $match) }}" class="btn btn-outline-secondary btn-sm btn-hover-azure">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-hover-azure" data-bs-toggle="modal" data-bs-target="#deleteMatchModal{{ $match->id }}">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete Match Modal -->
                        @auth
                            @if(auth()->user()->isScout())
                                <div class="modal fade" id="deleteMatchModal{{ $match->id }}" tabindex="-1" aria-labelledby="deleteMatchModalLabel{{ $match->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteMatchModalLabel{{ $match->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this match?</p>
                                                <p><strong>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</strong></p>
                                                <p><strong>Date:</strong> {{ $match->match_date->format('M d, Y h:i A') }}</p>
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
                    @empty
                        <div class="alert alert-info">
                            <p class="text-center mb-0">No upcoming matches scheduled.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer">
                    {{ $upcomingMatches->links() }}
                </div>
            </div>
        </div>
        
        <!-- Recent Results Tab -->
        <div class="tab-pane fade" id="recent" role="tabpanel" aria-labelledby="recent-tab">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recent Results</h5>
                </div>
                <div class="card-body">
                    @forelse($recentMatches as $match)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-md-end">
                                        <div class="d-flex flex-column align-items-center align-items-md-end">
                                            @if($match->homeTeam->logo)
                                                <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="img-fluid mb-2" style="max-height: 60px;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2" style="height: 60px; width: 60px;">
                                                    <i class="bi bi-shield-fill"></i>
                                                </div>
                                            @endif
                                            <h6>{{ $match->homeTeam->name }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="badge bg-secondary mb-2">{{ $match->match_date->format('l, M d, Y') }}</div>
                                            <h3 class="mb-2">{{ $match->home_score }} - {{ $match->away_score }}</h3>
                                            <div class="text-muted small">Full Time</div>
                                            <div class="text-muted small mt-2">
                                                <i class="bi bi-geo-alt"></i> {{ $match->venue }}
                                            </div>
                                            <div class="badge bg-secondary mt-2">{{ $match->competition }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-md-start">
                                        <div class="d-flex flex-column align-items-center align-items-md-start">
                                            @if($match->awayTeam->logo)
                                                <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="img-fluid mb-2" style="max-height: 60px;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2" style="height: 60px; width: 60px;">
                                                    <i class="bi bi-shield-fill"></i>
                                                </div>
                                            @endif
                                            <h6>{{ $match->awayTeam->name }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('matches.show', $match) }}" class="btn btn-outline-primary btn-sm btn-hover-azure">
                                        <i class="bi bi-info-circle"></i> Match Details
                                    </a>
                                    @auth
                                        @if(auth()->user()->isScout())
                                            <a href="{{ route('matches.edit', $match) }}" class="btn btn-outline-secondary btn-sm btn-hover-azure">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-hover-azure" data-bs-toggle="modal" data-bs-target="#deleteMatchModal{{ $match->id }}">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete Match Modal -->
                        @auth
                            @if(auth()->user()->isScout())
                                <div class="modal fade" id="deleteMatchModal{{ $match->id }}" tabindex="-1" aria-labelledby="deleteMatchModalLabel{{ $match->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteMatchModalLabel{{ $match->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this match?</p>
                                                <p><strong>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</strong></p>
                                                <p><strong>Result:</strong> {{ $match->home_score }} - {{ $match->away_score }}</p>
                                                <p><strong>Date:</strong> {{ $match->match_date->format('M d, Y') }}</p>
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
                    @empty
                        <div class="alert alert-info">
                            <p class="text-center mb-0">No recent match results available.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer">
                    {{ $recentMatches->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection