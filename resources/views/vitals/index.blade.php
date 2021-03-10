@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('vital.create') }}" class="btn btn-outline-primary btn-block mb-3">Record Vitals</a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Weight</th>
                                <th>BMI</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach( $vitals as $vital )
                            <?php
                                $bmi = round(($vital->weight / $vital->height / $vital->height) * 10000, 1);

                                $date_measured = \Carbon\Carbon::createFromFormat('Y-m-d', $vital->date_measured);

                                $class = '';

                                if($bmi <= 18.5){
                                    $class = 'danger';
                                }

                                if($bmi >= 18.5 && $bmi <= 24.9 ){
                                    $class = 'success';
                                }

                                if($bmi >= 25 && $bmi <= 29.9 ){
                                    $class = 'warning';
                                }

                                if($bmi >= 30 ){
                                    $class = 'danger';
                                }
                            ?>
                            <tr class="table-{{ $class }}">
                                <td>{{ $date_measured->format('d F, Y') }}</td>
                                <td>{{ $vital->weight }}</td>
                                <td>{{ $bmi }}</td>
                                <td align="right">
                                    <a href="{{ route('vital.show', $vital->id) }}">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
