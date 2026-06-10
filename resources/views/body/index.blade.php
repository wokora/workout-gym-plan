@extends('layouts.app')

@section('title', 'Body Sections')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div>
                        <h2 class="text-white mb-0">BODY SECTIONS</h2>
                        <p class="text-muted mb-0">Muscle groups targeted by catalog exercises.</p>
                    </div>
                    <div>
                        <a href="{{ route('body.create') }}" class="btn btn-primary d-inline-flex align-items-center mt-2 mt-sm-0">
                            <svg class="mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Add Body Section
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">ALL REGISTERED SECTIONS</div>

                    <div class="list-group list-group-flush">
                        @forelse( \App\Models\Body\Body::get() as $body_section )
                            <a href="{{ route('body.show', $body_section->id) }}" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-white font-weight-bold mb-1">{{ $body_section->name }}</h5>
                                    <small class="text-muted">{{ Str::limit($body_section->description, 60, '...') ?: 'No description provided.' }}</small>
                                </div>
                                <span class="btn btn-outline-primary btn-sm py-1 px-3">
                                    Configure
                                </span>
                            </a>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <svg class="mb-3" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                </svg>
                                <h5>No body sections found</h5>
                                <p class="mb-0">Add muscle target groups to populate the list.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
@endsection
