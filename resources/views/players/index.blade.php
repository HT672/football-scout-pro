@extends('layouts.app')

@section('title', 'Players')

@section('content')

 <!-- Link to the LayoutStyle CSS file -->
 <link rel="stylesheet" href="{{ asset('css/PlayerStyle.css') }}">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Players</h1>
        @auth
            @if(auth()->user()->isScout())
                <a href="{{ route('players.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Player
                </a>
            @endif
        @endauth
    </div>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Search Players</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('players.search') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="query" class="form-label">Player Name</label>
                    <input type="text" class="form-control" id="query" name="query" value="{{ request('query') }}" placeholder="Search by name...">
                </div>
                <div class="col-md-3">
                    <label for="position" class="form-label">Position</label>
                    <select class="form-select" id="position" name="position">
                        <option value="">All Positions</option>
                        @foreach($positions ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ request('position') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="team" class="form-label">Team</label>
                    <select class="form-select" id="team" name="team">
                        <option value="">All Teams</option>
                        @foreach($teams ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ request('team') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 btn-hover-azure">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Players List -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Players List
                @if(request('query') || request('position') || request('team'))
                    <small class="text-muted">(Filtered Results)</small>
                @endif
            </h5>
            
            @if(count($players) > 1)
                <form action="{{ route('players.compare') }}" method="GET" id="compareForm">
                    <button type="submit" class="btn btn-sm btn-outline-primary btn-hover-azure" id="compareBtn" disabled>
                        <i class="bi bi-bar-chart-fill"></i> Select Players
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 30px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Player</th>
                            <th>Position</th>
                            <th>Team</th>
                            <th>Nationality</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($players as $player)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input player-checkbox" type="checkbox" name="players[]" form="compareForm" value="{{ $player->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($player->photo)
                                            <img src="{{ asset('storage/' . $player->photo) }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $player->full_name }}">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('players.show', $player) }}" class="text-decoration-none">
                                                <strong class="gradient-text">{{ $player->full_name }}</strong>
                                            </a>
                                            @if($player->jersey_number)
                                                <span class="badge bg-dark ms-1">#{{ $player->jersey_number }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($player->position)->name }}</td>
                                <td>
                                    @if($player->team)
                                        <a href="{{ route('teams.show', $player->team) }}" class="text-decoration-none">
                                            {{ $player->team->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">No team</span>
                                    @endif
                                </td>
                                <td>{{ $player->nationality }}</td>
                                <td>{{ $player->age }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('players.show', $player) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @auth
                                            @if(auth()->user()->isScout())
                                                <a href="{{ route('players.edit', $player) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePlayerModal{{ $player->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Delete Player Modal -->
                            @auth
                                @if(auth()->user()->isScout())
                                    <div class="modal fade" id="deletePlayerModal{{ $player->id }}" tabindex="-1" aria-labelledby="deletePlayerModalLabel{{ $player->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deletePlayerModalLabel{{ $player->id }}">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete <strong>{{ $player->full_name }}</strong>? This action cannot be undone.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-search" style="font-size: 2rem;"></i>
                                    <p class="mt-2">No players found. Try adjusting your search criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $players->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const playerCheckboxes = document.querySelectorAll('.player-checkbox');
        const compareBtn = document.getElementById('compareBtn');
        
        selectAll?.addEventListener('change', function() {
            playerCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateCompareButton();
        });
        
        playerCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateCompareButton();
                
                // Update "Select All" checkbox
                const allChecked = Array.from(playerCheckboxes).every(c => c.checked);
                const someChecked = Array.from(playerCheckboxes).some(c => c.checked);
                
                if (selectAll) {
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = someChecked && !allChecked;
                }
            });
        });
        
        function updateCompareButton() {
            if (!compareBtn) return;
            
            const checkedCount = Array.from(playerCheckboxes).filter(c => c.checked).length;
            compareBtn.disabled = checkedCount < 2;
            
            if (checkedCount >= 2) {
                compareBtn.innerHTML = `<i class="bi bi-bar-chart-fill"></i> Compare (${checkedCount})`;
            } else {
                compareBtn.innerHTML = `<i class="bi bi-bar-chart-fill"></i> Compare Selected`;
            }
        }
    });
</script>
@endsection