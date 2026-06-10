@extends('layouts.app')

@section('title', 'Entries')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <a href="{{ route('meal.day.entry.create', [$meal->id, $day->id]) }}" class="btn btn-outline-primary btn-block mb-3">Add Food Entry to <strong>{{ $meal->name }}</strong></a>
            <div class="card">
                <div class="card-header">All Food Entries</div>

                <div class="list-group list-group-flush">
                    @foreach( $day->entry as $entry )
                        <a href="{{ route('meal.day.entry.show', [$meal->id, $day->id, $entry->id]) }}" class="list-group-item list-group-item-action">
                            {{ $entry->meal_type->name }}
                            <small class="text-muted ml-2">
                                ({{ $entry->foods->pluck('food.name')->join(', ') }})
                            </small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
