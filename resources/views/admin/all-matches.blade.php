@extends('layouts.app')

@section('title', 'All Matches Admin')

@section('content')
<div class="container">
    <h1>All Matches (Admin View)</h1>
    
    <div class="card">
        <div class="card-header">
            All Matches (Including Hidden)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Teams</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matches as $match)
                            <tr>
                                <td>{{ $match->id }}</td>
                                <td>{{ $match->homeTeam->name ?? 'Unknown' }} vs {{ $match->awayTeam->name ?? 'Unknown' }}</td>
                                <td>{{ $match->match_date->format('Y-m-d H:i') }}</td>
                                <td>{{ $match->status }}</td>
                                <td>{{ $match->home_score }} - {{ $match->away_score }}</td>
                                <td>
                                    <form action="{{ route('matches.destroy', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this match?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection