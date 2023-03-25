@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div class="container">
        @include('components.leaderboard')

        <div class="d-flex justify-content-center mt-2">
            {{ $donors->onEachSide(0)->links() }}
        </div>
    </div>
@endsection
@section('javascript')
@endsection
