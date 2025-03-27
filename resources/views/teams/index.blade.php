@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Teams</h1>
        @auth
            @if(auth()->user()->isScout())
                <a href="{{ route('teams.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Team
                </a>
            @endif
        @endauth
    </div>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($teams as $team)
            <div class="col">
                <div class="card h-100">
                    <div class="card-header bg-light text-center py-3">
                        @if($team->logo)
                            <img src="{{ asset('storage/' . $team->logo) }}" class="img-fluid mb-2" style="max-height: 100px;" alt="{{ $team->name }}">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2 mx-auto" style="height: 100px; width: 100px;">
                                <i class="bi bi-shield-fill" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <h5 class="card-title mb-0">{{ $team->name }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted mb-2">
                            <strong>Country:</strong> {{ $team->country }}<br>
                            <strong>League:</strong> {{ $team->league }}<br>
                            <strong>Players:</strong> {{ $team->players_count }}
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('teams.show', $team) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Team
                            </a>
                            @auth
                                @if(auth()->user()->isScout())
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teams.edit', $team) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTeamModal{{ $team->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Delete Team Modal -->
            @auth
                @if(auth()->user()->isScout())
                    <div class="modal fade" id="deleteTeamModal{{ $team->id }}" tabindex="-1" aria-labelledby="deleteTeamModalLabel{{ $team->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteTeamModalLabel{{ $team->id }}">Confirm Delete</h5>
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
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <p class="text-center mb-0">No teams found. Create a team to get started.</p>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $teams->links() }}
    </div>
</div>
@endsection