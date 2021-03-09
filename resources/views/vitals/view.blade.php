@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('vital.edit', $vital->id) }}" class="btn btn-outline-primary btn-block mb-3">Edit Vital</a>

                <div class="card">


                    <div class="card-footer text-right">
                        <form action="{{ route('vital.destroy', $vital->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="delete" />
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>


                </div>


            </div>
        </div>
    </div>
@endsection
