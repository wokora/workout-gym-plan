@extends('layouts.app')

@section('title', 'Manage Exercise')

@section('content')
<div class="space-y-6">
    <div class="card rounded-xl border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
        <div class="flex items-center justify-between border-b border-slate-150 px-5 py-4 dark:border-navy-600">
            <span class="font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-navy-200">
                Manage Routine Exercise
            </span>
            <a href="{{ route('workout.day.show', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id]) }}" class="text-xs font-semibold text-primary hover:underline dark:text-accent-light">
                &larr; Back to Routine
            </a>
        </div>

        <form action="{{ route('workout.day.exercise.update', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" method="post" class="p-5 sm:p-6 space-y-5">
            @method('PATCH')
            @csrf
            <div>
                <label for="exercise" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Exercise</label>
                <select name="exercise" id="exercise"
                    class="form-select w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('exercise') border-error @enderror">
                    <option value="">Select Exercise</option>
                    @foreach(\App\Models\Exercise\Exercise::get() as $exercise)
                        <option value="{{ $exercise->id }}" @if(old('exercise', $workout_exercise->exercise->id) == $exercise->id) selected @endif>{{ $exercise->name }}</option>
                    @endforeach
                </select>
                @error('exercise')
                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="sets" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Sets Target</label>
                    <input type="number" name="sets" value="{{ old('sets', $workout_exercise->sets) }}" id="sets"
                        class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('sets') border-error @enderror">
                    @error('sets')
                        <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="reps" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">Reps Target</label>
                    <input type="number" name="reps" value="{{ old('reps', $workout_exercise->reps) }}" id="reps"
                        class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('reps') border-error @enderror">
                    @error('reps')
                        <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-150 dark:border-navy-600">
                <button type="button" onclick="document.getElementById('delete-routine-exercise-form').submit();"
                    class="btn h-9 rounded-lg border border-error/30 px-3.5 text-xs font-semibold text-error hover:bg-error/10">
                    Delete Exercise
                </button>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('workout.day.show', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id]) }}"
                        class="btn h-9 rounded-lg border border-slate-300 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-200 dark:hover:bg-navy-600">
                        Cancel
                    </a>
                    <button type="submit" class="btn h-9 rounded-lg bg-primary px-5 text-xs font-semibold text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-routine-exercise-form" action="{{ route('workout.day.exercise.destroy', [$workout_exercise->workout_day->workout->id, $workout_exercise->workout_day->id, $workout_exercise->id]) }}" method="post" onsubmit="return confirm('Are you sure you want to remove this exercise from this day?');" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
