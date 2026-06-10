@extends('layouts.app')

@section('title', 'Exercise')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div>
                        <h2 class="text-white mb-0">{{ $exercise->name }}</h2>
                        <a href="{{ route('exercise.index') }}" class="text-muted font-weight-bold" style="font-size: 0.85rem; text-decoration: none;">&larr; Back to Catalog</a>
                    </div>
                    <div>
                        <a href="{{ route('exercise.edit', $exercise->id) }}" class="btn btn-primary btn-sm">
                            Edit Exercise
                        </a>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card">
                    <div class="card-header">EXERCISE GUIDE & DETAILS</div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="text-muted">Description</label>
                            <div class="p-3 rounded text-white" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); min-height: 100px; line-height: 1.7;">
                                {{ $exercise->description ?: 'No detailed description or guide has been written for this exercise yet.' }}
                            </div>
                        </div>

                        <!-- Target muscle groups placeholder/list if models support relationships -->
                        <div class="form-group mb-0">
                            <label class="text-muted">Target Muscle Groups / Body Sections</label>
                            <div class="d-flex flex-wrap" style="gap: 0.5rem;">
                                @forelse($exercise->body_section ?? [] as $body)
                                    <span class="badge badge-dark py-2 px-3 text-primary font-weight-bold" style="background: rgba(163, 230, 53, 0.1); border: 1px solid rgba(163, 230, 53, 0.2); font-size: 0.85rem;">
                                        {{ $body->name }}
                                    </span>
                                @empty
                                    <span class="text-muted" style="font-size: 0.9rem;">No body sections mapped to this exercise.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
