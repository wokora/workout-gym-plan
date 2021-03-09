@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Record Vital</div>

                    <form action="{{ route('vital.store') }}" method="post">
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label>Date Measured</label>
                                <input type="date" name="date" value="{{ old('date', '') }}" id="date" class="form-control @error('date') is-invalid @enderror" placeholder="Date">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Height</label>
                                        <input type="number" step="any" name="height" value="{{ old('height', '') }}" id="name" class="form-control @error('height') is-invalid @enderror" placeholder="Height">
                                        @error('height')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Weight</label>
                                        <input type="number" step="any" name="weight" value="{{ old('weight', '') }}" id="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="Weight">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Save Vitals</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection
