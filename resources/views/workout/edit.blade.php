@extends('layouts.app')

@section('title', 'Edit Workout')

@section('content')
<div class="space-y-6">
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex items-center justify-between border-b border-slate-150 px-5 py-4 dark:border-navy-600">
            <span class="font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                Edit Workout Plan
            </span>
            <a href="{{ route('workout.show', $workout->id) }}" class="text-xs font-semibold text-primary hover:underline dark:text-accent-light">
                &larr; Back to Plan
            </a>
        </div>

        <form action="{{ route('workout.update', $workout->id) }}" method="post" class="p-5 sm:p-6 space-y-5">
            @method('PATCH')
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Workout Plan Name</label>
                <input type="text" name="name" value="{{ old('name', $workout->name) }}" id="name"
                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror">
                @error('name')
                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>



            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="start_time" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Execution Start Time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $workout->start_time) }}" id="start_time"
                        class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('start_time') border-error @enderror">
                    @error('start_time')
                        <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Execution End Time</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $workout->end_time) }}" id="end_time"
                        class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('end_time') border-error @enderror">
                    @error('end_time')
                        <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-2">Workout Days</label>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-7">
                    @foreach(\App\Models\Day\Day::get() as $day)
                        <div>
                            <input type="checkbox" value="{{ $day->id }}" name="day[{{ $day->id }}]" id="customCheck{{ $day->id }}"
                                class="peer hidden"
                                @if($workout->workout_day()->where('day_id', $day->id)->count() > 0) checked @endif>
                            <label for="customCheck{{ $day->id }}"
                                class="block cursor-pointer select-none rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-center text-xs font-semibold text-slate-600 transition-all hover:bg-slate-100 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white dark:border-navy-600 dark:bg-navy-800 dark:text-navy-200 dark:peer-checked:border-accent dark:peer-checked:bg-accent dark:peer-checked:text-white">
                                {{ $day->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('day')
                    <span class="mt-2 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-150 dark:border-navy-600">
                <a href="{{ route('workout.show', $workout->id) }}" class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                    Cancel
                </a>
                <button type="submit" class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
