@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="space-y-4 sm:space-y-5 lg:space-y-6">

    <!-- Header -->
    <div class="flex flex-col justify-between space-y-2 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-navy-50 sm:text-2xl font-inter">
                Profile Settings
            </h1>
            <p class="text-xs text-slate-400 dark:text-navy-300">
                Manage your personal details, date of birth, and biological baseline.
            </p>
        </div>
        <a href="{{ route('index') }}" class="btn space-x-1.5 rounded-lg border border-slate-300 px-3.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Dashboard</span>
        </a>
    </div>

    <!-- Flash message -->
    @if(session('success'))
        <div class="alert flex rounded-lg bg-success/10 px-4 py-3 text-success dark:bg-success/15">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="ml-2 text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">

        <!-- Left Column: Edit Profile Form (8 Cols) -->
        <div class="col-span-12 lg:col-span-8 space-y-4 sm:space-y-5">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card p-4 sm:p-5 shadow-soft space-y-5">
                    <div class="flex items-center space-x-3 pb-3 border-b border-slate-150 dark:border-navy-600">
                        <div class="avatar size-12">
                            <div class="is-initial rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light font-bold text-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h2 class="text-sm-plus font-bold text-slate-800 dark:text-navy-100">
                                Personal Information
                            </h2>
                            <p class="text-tiny text-slate-400 dark:text-navy-300">
                                Update your identification and date of birth.
                            </p>
                        </div>
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">
                            Full Name <span class="text-error">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror">
                        @error('name')
                            <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300 mb-1.5">
                            Email Address <span class="text-error">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('email') border-error @enderror">
                        @error('email')
                            <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Birth Field (Nullable / Optional) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="date_of_birth" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-navy-300">
                                Date of Birth <span class="text-slate-400 text-tiny normal-case font-normal">(Optional)</span>
                            </label>
                            @if($userAge !== null)
                                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/15 dark:text-accent-light px-2.5 py-0.5 text-tiny font-bold">
                                    Current Age: {{ $userAge }} years
                                </span>
                            @endif
                        </div>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                            max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('date_of_birth') border-error @enderror">
                        <p class="mt-1.5 text-tiny text-slate-400 dark:text-navy-300">
                            Your age is used on the dashboard to calculate clinical fitness indicators (Max Heart Rate, Basal Metabolic Rate, and age-adjusted body fat analysis). Leave blank if you prefer not to specify.
                        </p>
                        @error('date_of_birth')
                            <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Update (Optional) -->
                    <div class="pt-4 border-t border-slate-150 dark:border-navy-600 space-y-4">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-navy-100">
                                Change Password <span class="text-slate-400 text-tiny normal-case font-normal">(Leave blank to keep current)</span>
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="password" class="block text-xs font-medium text-slate-500 dark:text-navy-300 mb-1">
                                    New Password
                                </label>
                                <input type="password" name="password" id="password" autocomplete="new-password"
                                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('password') border-error @enderror">
                                @error('password')
                                    <span class="mt-1 block text-tiny-plus text-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-slate-500 dark:text-navy-300 mb-1">
                                    Confirm New Password
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm text-slate-800 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3 flex justify-end">
                        <button type="submit"
                            class="btn space-x-1.5 rounded-lg bg-primary px-5 py-2 text-xs font-semibold text-white shadow-soft hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Profile Details</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column: Biological Baseline Card (4 Cols) -->
        <div class="col-span-12 lg:col-span-4 space-y-4">
            <div class="card p-4 sm:p-5 shadow-soft space-y-4">
                <div class="flex items-center space-x-2 pb-3 border-b border-slate-150 dark:border-navy-600">
                    <div class="mask is-star flex size-8 shrink-0 items-center justify-center bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-navy-100">
                        Biological Baseline
                    </h2>
                </div>

                <div class="space-y-3">
                    <!-- Age Metric -->
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-navy-750">
                        <div>
                            <p class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300 font-semibold">Calculated Age</p>
                            <p class="text-base font-bold text-slate-800 dark:text-navy-100">
                                {{ $userAge !== null ? $userAge . ' years' : 'Not Set' }}
                            </p>
                        </div>
                        <div class="text-right">
                            @if($userAge !== null)
                                <span class="badge rounded bg-success/10 text-success dark:bg-success/15 px-2 py-0.5 text-tiny font-bold">
                                    Active
                                </span>
                            @else
                                <span class="badge rounded bg-slate-100 text-slate-400 dark:bg-navy-600 dark:text-navy-300 px-2 py-0.5 text-tiny font-medium">
                                    Optional
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Max HR Metric -->
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-navy-750">
                        <div>
                            <p class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300 font-semibold">Predicted Max HR</p>
                            <p class="text-base font-bold text-rose-500">
                                {{ $user->maxHeartRate() ? $user->maxHeartRate() . ' bpm' : '--' }}
                            </p>
                        </div>
                        <span class="text-tiny text-slate-400 dark:text-navy-300">
                            220 - Age
                        </span>
                    </div>

                    <!-- BMR Metric -->
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-navy-750">
                        <div>
                            <p class="text-tiny uppercase tracking-wider text-slate-400 dark:text-navy-300 font-semibold">Basal Metabolic Rate</p>
                            <p class="text-base font-bold text-info">
                                {{ $user->bmr() ? number_format($user->bmr()) . ' kcal' : '--' }}
                            </p>
                        </div>
                        <span class="text-tiny text-slate-400 dark:text-navy-300">
                            Mifflin-St Jeor
                        </span>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-150 dark:border-navy-600">
                    <a href="{{ route('vital.index') }}" class="btn h-8 w-full rounded-lg border border-primary/30 text-xs font-semibold text-primary hover:bg-primary/10 dark:border-accent/30 dark:text-accent-light flex items-center justify-center space-x-1">
                        <span>View All Vitals History</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
