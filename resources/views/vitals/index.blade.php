@extends('layouts.app')

@section('title', 'Vitals')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="text-white mb-0">VITALS LOG</h2>
                    <p class="text-muted mb-0">Track your weight and body mass index (BMI) progress.</p>
                </div>
                <div>
                    <a href="{{ route('vital.create') }}" class="btn btn-primary d-inline-flex align-items-center mt-2 mt-sm-0">
                        <svg class="mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Record Vitals
                    </a>
                </div>
            </div>

            @if($current_vital)
                <!-- Metrics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card p-3 text-center h-100">
                            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Current Weight</small>
                            <h2 class="text-white font-weight-bold mt-2 mb-0">{{ $current_vital->weight }} <span style="font-size: 1.1rem; color: #94a3b8;">kg</span></h2>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card p-3 text-center h-100">
                            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Height</small>
                            <h2 class="text-white font-weight-bold mt-2 mb-0">{{ $current_vital->height }} <span style="font-size: 1.1rem; color: #94a3b8;">cm</span></h2>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <?php
                            $latest_bmi = round(($current_vital->weight / ($current_vital->height * $current_vital->height) ) * 10000, 1);
                            $bmi_color = '#10b981'; // Healthy green default
                            $bmi_label = 'Healthy';

                            if($latest_bmi < 18.5) {
                                $bmi_color = '#06b6d4'; // Underweight blue
                                $bmi_label = 'Underweight';
                            } elseif($latest_bmi >= 25 && $latest_bmi < 30) {
                                $bmi_color = '#facc15'; // Overweight yellow
                                $bmi_label = 'Overweight';
                            } elseif($latest_bmi >= 30) {
                                $bmi_color = '#ef4444'; // Obese red
                                $bmi_label = 'Obese';
                            }
                        ?>
                        <div class="card p-3 text-center h-100" style="border-color: rgba({{ $latest_bmi >= 25 ? '250,204,21' : '16,185,129' }}, 0.15) !important;">
                            <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Current BMI</small>
                            <h2 class="font-weight-bold mt-2 mb-0" style="color: {{ $bmi_color }};">{{ $latest_bmi }}</h2>
                            <span class="badge mt-2 mx-auto py-1 px-3" style="background: rgba({{ $latest_bmi >= 25 ? '250,204,21' : '16,185,129' }}, 0.1); color: {{ $bmi_color }}; border: 1px solid rgba({{ $latest_bmi >= 25 ? '250,204,21' : '16,185,129' }}, 0.2); font-size: 0.7rem; font-weight: 700; border-radius: 20px; width: fit-content;">{{ $bmi_label }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Import & Export Card -->
            <div class="card mb-4">
                <div class="card-header">IMPORT & EXPORT DATA</div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Export Block -->
                        <div class="col-md-6 mb-4 mb-md-0 border-right border-secondary" style="border-right-style: dashed !important; border-right-color: rgba(255, 255, 255, 0.08) !important;">
                            <h5 class="text-white font-weight-bold mb-2">Export Data</h5>
                            <p class="text-muted small">Download your full history of logged height and weight metrics as a JSON file.</p>
                            <a href="{{ route('vital.export') }}" class="btn btn-outline-primary btn-sm px-4">
                                <svg class="mr-2" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                                Export JSON
                            </a>
                        </div>

                        <!-- Import Block -->
                        <div class="col-md-6">
                            <h5 class="text-white font-weight-bold mb-2">Import Data</h5>
                            <p class="text-muted small">Upload a previously exported JSON vitals file. Existing dates will be updated.</p>
                            <form action="{{ route('vital.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
                                @csrf
                                <div class="flex-grow-1">
                                    <input type="file" name="vitals_file" accept=".json" required class="form-control-file text-muted @error('vitals_file') is-invalid @enderror" style="font-size: 0.85rem;">
                                    @error('vitals_file')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm py-2 px-4 mt-2 mt-sm-0">
                                    Import JSON
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Card -->
            <div class="card">
                <div class="card-header">VITAL HISTORY LOG</div>

                @if(count($vitals) > 0)
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date Measured</th>
                                    <th>Weight</th>
                                    <th>Height</th>
                                    <th>Calculated BMI</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach( $vitals as $vital )
                                <?php
                                    $bmi = round(($vital->weight / ($vital->height * $vital->height) ) * 10000, 1);
                                    $date_measured = \Carbon\Carbon::parse($vital->date_measured);

                                    $pill_bg = 'rgba(16, 185, 129, 0.1)';
                                    $pill_border = 'rgba(16, 185, 129, 0.2)';
                                    $pill_color = '#10b981';
                                    $pill_text = 'Healthy';

                                    if($bmi < 18.5){
                                        $pill_bg = 'rgba(6, 182, 212, 0.1)';
                                        $pill_border = 'rgba(6, 182, 212, 0.2)';
                                        $pill_color = '#06b6d4';
                                        $pill_text = 'Underweight';
                                    } elseif($bmi >= 25 && $bmi <= 29.9 ){
                                        $pill_bg = 'rgba(250, 204, 21, 0.1)';
                                        $pill_border = 'rgba(250, 204, 21, 0.2)';
                                        $pill_color = '#facc15';
                                        $pill_text = 'Overweight';
                                    } elseif($bmi >= 30 ){
                                        $pill_bg = 'rgba(239, 68, 68, 0.1)';
                                        $pill_border = 'rgba(239, 68, 68, 0.2)';
                                        $pill_color = '#ef4444';
                                        $pill_text = 'Obese';
                                    }
                                ?>
                                <tr>
                                    <td class="font-weight-bold text-white">{{ $date_measured->format('d M, Y') }}</td>
                                    <td>{{ $vital->weight }} kg</td>
                                    <td>{{ $vital->height }} cm</td>
                                    <td>
                                        <span class="badge py-1 px-3" style="background: {{ $pill_bg }}; color: {{ $pill_color }}; border: 1px solid {{ $pill_border }}; font-size: 0.75rem; border-radius: 12px; font-weight: 600;">
                                            {{ $bmi }} &bull; {{ $pill_text }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <a href="{{ route('vital.show', $vital->id) }}" class="btn btn-secondary btn-sm py-1 px-3">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted card-body">
                        <svg class="mb-3" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            <path d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <h5>No vitals logged yet</h5>
                        <p class="mb-4">Log your height and weight measurements to track health trends.</p>
                        <a href="{{ route('vital.create') }}" class="btn btn-outline-primary btn-sm">Record First Entry</a>
                    </div>
                @endif
            </div>
        </div>
@endsection
