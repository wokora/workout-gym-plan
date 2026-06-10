@extends('layouts.app')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Create Meal Plan Day</div>

                    <form action="{{ route('meal.day.store', $meal->id) }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Day Description</label>
                                <input type="text" name="name" value="{{ old('name', '') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Day Description">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Save Day</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
@endsection
