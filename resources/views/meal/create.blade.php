@extends('layouts.app')

@section('title', 'Create Meal Plan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">CREATE NEW MEAL PLAN</div>

                <form action="{{ route('meal.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="name">Meal Plan Name</label>
                            <input type="text" name="name" value="{{ old('name', '') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Weight Loss Plan, Bulk Plan">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" value="{{ old('start_date', '') }}" id="start_date" class="form-control @error('start_date') is-invalid @enderror">
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date', '') }}" id="end_date" class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label>Target Days</label>
                            <div class="day-selector-grid">
                                @foreach(\App\Models\Day\Day::get() as $day)
                                    <div class="day-selector-item">
                                        <input type="checkbox" value="{{ $day->id }}" name="day[{{ $day->id }}]" id="customCheck{{ $day->id }}" @if(is_array(old('day')) && in_array($day->id, old('day'))) checked @endif>
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
                        <a href="{{ route('meal.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
