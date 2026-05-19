@extends('layouts.index')

@section('content')
  <div class="gs-main-page">
    {{-- GREETINGS HOLDER --}}
    <div class="gs-card rounded-lg p-4">
      <div class="flex items-center gap-2 mb-3">
        <h1 class="text-gray-300 text-3xl font-bold">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</h1>
        <i data-lucide="hand" class="text-gray-300 w-8 h-8"></i>
      </div>


      <p class="gs-secondary-text">Here’s what’s happening in your school system today.</p>
    </div>

    {{-- CARDS --}}
    <div class="flex gap-4 justify-evenly">
      <div class="gs-card rounded-lg p-4 w-full">
        <div class="bg-[#20224A] w-fit p-2 rounded-md mb-2">
          <i data-lucide="users-round" class="text-[#8B84FF] w-6 h-6"></i>
        </div>

        <div class="w-fit flex flex-col gap-1">
          <h1 class="text-gray-300 text-3xl font-semibold">{{ $totalStudents }}</h1>
          <p class="gs-secondary-text">Total Students</p>
          <p class="gs-success-bg gs-success-text text-xs px-1 rounded-md">+{{ $newStudentsThisMonth }} this month</p>
        </div>
      </div>

      <div class="gs-card rounded-lg p-4 w-full">
        <div class="bg-[#2F2626] w-fit p-2 rounded-md mb-2">
          <i data-lucide="chart-no-axes-column" class="text-[#F59E0B] w-6 h-6"></i>
        </div>

        <div class="w-fit flex flex-col gap-1">
          <h1 class="text-gray-300 text-3xl font-semibold">{{ $totalGradeLevels }}</h1>
          <p class="gs-secondary-text">Grade Levels</p>
          <p class="gs-success-bg gs-success-text text-xs px-1 rounded-md">{{ $gradeLevelsRange }}</p>
        </div>
      </div>

      <div class="gs-card rounded-lg p-4 w-full">
        <div class="bg-[#182343] w-fit p-2 rounded-md mb-2">
          <i data-lucide="panels-top-left" class="text-[#60A5FA] w-6 h-6"></i>
        </div>

        <div class="w-fit flex flex-col gap-1">
          <h1 class="text-gray-300 text-3xl font-semibold">{{ $totalSections }}</h1>
          <p class="gs-secondary-text">Sections</p>
          <p class="gs-success-bg gs-success-text text-xs px-1 rounded-md">{{ $sectionNamesList }}</p>
        </div>
      </div>
    </div>

    {{-- RECENT ACTIVITY --}}
    <div>
      <h1 class="text-gray-300">Recent Activity</h1>
      <div class="gs-card rounded-lg p-4 mt-4 flex flex-col gap-2 gs-secondary-text text-sm">
        @forelse($recentActivities as $activity)
          <div class="flex justify-between items-center border-b-[0.5px] border-[#545878] pb-2 last:border-b-0 last:pb-0">
            <h1>{{ $activity->description }}</h1>
            <span class="text-xs opacity-60">{{ $activity->created_at->diffForHumans() }}</span>
          </div>
        @empty
          <div class="py-2 text-center">
            <p class="text-sm opacity-60">No recent activity found.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection