@extends('layouts.app')

@section('title', 'Edit Vital')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>EDIT BODY METRICS</span>
                    <a href="{{ route('vital.show', $vital->id) }}" class="text-primary font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Detail</a>
                </div>

                <form action="{{ route('vital.update', [$vital->id]) }}" method="post">
                    @method('PATCH')
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="date">Date Measured</label>
                            <input type="date" name="date" value="{{ old('date', $vital->date_measured) }}" id="date" class="form-control @error('date') is-invalid @enderror">
                            @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="weight">Weight (KG)</label>
                                    <input type="number" step="any" name="weight" value="{{ old('weight', $vital->weight) }}" id="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="Weight">
                                    @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="height">Height (CM)</label>
                                    <input type="number" step="any" name="height" value="{{ old('height', $vital->height) }}" id="height" class="form-control @error('height') is-invalid @enderror" placeholder="Height">
                                    @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <button type="button" onclick="if(confirm('Are you sure you want to delete this vital record?')) { document.getElementById('delete-vital-form').submit(); }" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">
                            Delete Record
                        </button>
                        <div>
                            <a href="{{ route('vital.show', $vital->id) }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <form id="delete-vital-form" action="{{ route('vital.destroy', $vital->id) }}" method="post" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
