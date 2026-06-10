@extends('layouts.app')

@section('title', 'Body Section')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div>
                        <h2 class="text-white mb-0">{{ $body->name }}</h2>
                        <a href="{{ route('body.index') }}" class="text-muted font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Sections</a>
                    </div>
                    <div>
                        <a href="{{ route('body.edit', $body->id) }}" class="btn btn-primary btn-sm">
                            Edit Section
                        </a>
                    </div>
                </div>

                <!-- Detail Card -->
                <div class="card">
                    <div class="card-header">SECTION DESCRIPTION & INFO</div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="text-muted">Description</label>
                            <div class="p-3 rounded text-white" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); min-height: 100px; line-height: 1.7;">
                                {{ $body->description ?: 'No description recorded for this body section.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
