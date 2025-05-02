@extends('layouts.admin')
@section('title', 'Configuration')
@section('content')
    <h1>Configuration</h1>

    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.config.update') }}" method="POST">
                @csrf
                
                <div class="form-group">
                <div class="mb-3">
                    <label for="app_name">Nom de l'application</label>
                    <input type="text" class="form-control" id="app_name" name="app_name" value="{{ env('APP_NAME') }}" required>
                </div>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
@endsection


