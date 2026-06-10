@extends('layouts.app')

@section('title', 'Vital Record')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">VITAL DETAIL</h2>
                    <a href="{{ route('vital.index') }}" class="text-muted font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Log</a>
                </div>
                <div class="d-flex" style="gap: 0.5rem;">
                    <a href="{{ route('vital.edit', $vital->id) }}" class="btn btn-outline-primary btn-sm">Edit Log</a>
                    <form action="{{ route('vital.destroy', $vital->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vital record?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm" style="border-color: rgba(239, 68, 68, 0.4); color: #ef4444;">Delete</button>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card text-center p-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Date Measured</small>
                    <h4 class="text-white font-weight-bold mb-4 mt-1">{{ \Carbon\Carbon::parse($vital->date_measured)->format('F d, Y') }}</h4>

                    <div class="row mt-4">
                        <div class="col-6 col-md-4 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);">
                                <small class="text-muted">Weight</small>
                                <h3 class="text-white font-weight-bold mt-1 mb-0">{{ $vital->weight }} kg</h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="p-3 rounded" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);">
                                <small class="text-muted">Height</small>
                                <h3 class="text-white font-weight-bold mt-1 mb-0">{{ $vital->height }} cm</h3>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?php
                                $bmi = round(($vital->weight / ($vital->height * $vital->height) ) * 10000, 1);
                                $bmi_color = '#10b981';
                                if($bmi < 18.5) $bmi_color = '#06b6d4';
                                if($bmi >= 25 && $bmi < 30) $bmi_color = '#facc15';
                                if($bmi >= 30) $bmi_color = '#ef4444';
                            ?>
                            <div class="p-3 rounded" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);">
                                <small class="text-muted">Calculated BMI</small>
                                <h3 class="font-weight-bold mt-1 mb-0" style="color: {{ $bmi_color }};">{{ $bmi }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
