

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <p>Welcome, {{ $user->name }}</p>

                    <h5>Active Sessions:</h5>
                    <ul>
                        @foreach($userSessions[$user->id] as $sessionId)
                            <li>{{ $sessionId }}</li>
                        @endforeach
                    </ul>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
