@extends('layouts.app')

@section('title', 'Add Vital')

@section('content')
    <?php
        $height = '';
        $weight = '';
        if(auth()->user()->vitals->count() > 0){
            $vitals = auth()->user()->vitals()->orderBy('id','desc')->first();
            $height = $vitals->height;
            $weight = $vitals->weight;
        }
    ?>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>RECORD BODY METRICS</span>
                    <a href="{{ route('vital.index') }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Log</a>
                </div>

                <form action="{{ route('vital.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="date">Date Measured</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" id="date" class="form-control @error('date') is-invalid @enderror">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="weight">Weight (KG)</label>
                                    <input type="number" step="any" name="weight" value="{{ old('weight', $weight) }}" id="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="e.g. 75.5">
                                    @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="height">Height (CM)</label>
                                    <input type="number" step="any" name="height" value="{{ old('height', $height) }}" id="height" class="form-control @error('height') is-invalid @enderror" placeholder="e.g. 180">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('vital.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Metrics</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
