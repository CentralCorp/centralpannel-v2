@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'Welcome to Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Statistiques -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Nombre de comptes</h6>
                            <h2 class="mb-0">{{ $userCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes de version -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Notes de version</h5>
                    <div class="list-group">
                        @if(isset($releases) && count($releases) > 0)
                            @foreach($releases as $release)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <a href="{{ $release->link }}" target="_blank" class="text-decoration-none">
                                                Version {{ $release->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ $release->date }}</small>
                                    </div>
                                    <p class="mb-1">{{ $release->description }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Aucune note de version disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
