@extends('layouts.app')

@section('title', 'Edit Meal Plan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>EDIT MEAL PLAN</span>
                    <a href="{{ route('meal.show', $meal->id) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Plan</a>
                </div>

                <form action="{{ route('meal.update', $meal->id) }}" method="post">
                    @method('PATCH')
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="name">Meal Plan Name</label>
                            <input type="text" name="name" value="{{ old('name', $meal->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Meal Plan Name">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" value="{{ old('start_date', $meal->start_date) }}" id="start_date" class="form-control @error('start_date') is-invalid @enderror">
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date', $meal->end_date) }}" id="end_date" class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label>Meal Plan Days</label>
                            <div class="day-selector-grid">
                                @foreach(\App\Models\Day\Day::get() as $day)
                                    <div class="day-selector-item">
                                        <input type="checkbox" value="{{ $day->id }}" name="day[{{ $day->id }}]" id="customCheck{{ $day->id }}" @if( $meal->meal_plan_day()->where('day_id', $day->id)->count() > 0 ) checked @endif>
                                        <label for="customCheck{{ $day->id }}">{{ $day->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('day')
                                <div class="text-danger mt-2" style="font-size: 80%">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('meal.show', $meal->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
