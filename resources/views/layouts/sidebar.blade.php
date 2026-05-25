@php
  $user = auth()->user();
@endphp

<aside id="sidebar"
  class="fixed top-0 left-0 z-50 h-screen w-64 max-w-[min(100vw,16rem)] p-4 sm:p-5 gs-secondary-bg flex flex-col justify-between border-r border-[#545878] -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

  <div>
    <div class="relative border-b-[0.5px] pb-6 sm:pb-8 border-[#545878]">
      <div class="flex items-center justify-between gap-2">
        <div class="flex items-center gap-3 min-w-0">
          <div class="flex justify-center px-2 py-1 bg-[#6366F1] rounded-lg shrink-0">
            <i data-lucide="layers" class="text-white w-5 h-5" stroke-width="2"></i>
          </div>
          <h1 class="text-white text-lg sm:text-xl font-semibold truncate">GRADESYNC</h1>
        </div>
        <button type="button" id="sidebar-close" aria-label="Close menu"
          class="lg:hidden shrink-0 p-1.5 rounded-lg text-gray-400 hover:bg-[#22273D] hover:text-white transition">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>
      <p class="mt-2 ml-11 sm:absolute sm:left-3 gs-secondary-text text-xs sm:text-sm">Admin portal</p>
    </div>

    <div class="space-y-8 sm:space-y-10 mt-6 sm:mt-10">

      <div>
        <h2 class="text-xs gs-secondary-text mb-2 ml-2 sm:ml-4">MAIN</h2>

        <ul>
          <li>
            <a href="{{ route('dashboard') }}"
              class="w-full py-2 px-3 text-base sm:text-lg mb-1 flex gap-2 items-center gs-sidebar-hover-active
              {{ request()->routeIs('dashboard') ? 'gs-sidebar-active' : 'gs-secondary-text' }}">
              <i data-lucide="box" class="w-6 h-6 sm:w-8 sm:h-6 shrink-0"></i>
              <span class="truncate">Overview</span>
            </a>
          </li>

          <li>
            <a href="{{ route('students.index') }}"
              class="w-full py-2 px-3 text-base sm:text-lg mb-1 flex gap-2 items-center gs-sidebar-hover-active
              {{ request()->routeIs('students.*') ? 'gs-sidebar-active' : 'gs-secondary-text' }}">
              <i data-lucide="circle-user-round" class="w-6 h-6 sm:w-8 sm:h-6 shrink-0"></i>
              <span class="truncate">Students</span>
            </a>
          </li>

          <li>
            <a href="{{ route('sections.index') }}"
              class="w-full py-2 px-3 text-base sm:text-lg mb-1 flex gap-2 items-center gs-sidebar-hover-active
              {{ request()->routeIs('sections.*') ? 'gs-sidebar-active' : 'gs-secondary-text' }}">
              <i data-lucide="users-round" class="w-6 h-6 sm:w-8 sm:h-6 shrink-0"></i>
              <span class="truncate">Sections</span>
            </a>
          </li>

          <li>
            <a href="{{ route('subjects.index') }}"
              class="w-full py-2 px-3 text-base sm:text-lg mb-1 flex gap-2 items-center gs-sidebar-hover-active
              {{ request()->routeIs('subjects.*') ? 'gs-sidebar-active' : 'gs-secondary-text' }}">
              <i data-lucide="book-copy" class="w-6 h-6 sm:w-8 sm:h-6 shrink-0"></i>
              <span class="truncate">Subjects</span>
            </a>
          </li>

          <li>
            <a href="{{ route('grades.index') }}"
              class="w-full py-2 px-3 text-base sm:text-lg mb-1 flex gap-2 items-center gs-sidebar-hover-active
              {{ request()->routeIs('grades.*') ? 'gs-sidebar-active' : 'gs-secondary-text' }}">
              <i data-lucide="chart-no-axes-column" class="w-6 h-6 sm:w-8 sm:h-6 shrink-0"></i>
              <span class="truncate">Grades</span>
            </a>
          </li>
        </ul>
      </div>

      <div>
        <h2 class="text-xs gs-secondary-text mb-3 ml-2 sm:ml-4">REPORTS</h2>

        <ul>
          <li>
            <a href="{{ route('grade-reports.index') }}"
              class="w-full py-2 px-3 text-base sm:text-lg mb-1 flex gap-2 items-center gs-sidebar-hover-active
              {{ request()->routeIs('grade-reports.*') ? 'gs-sidebar-active' : 'gs-secondary-text' }}">
              <i data-lucide="notepad-text" class="w-6 h-6 sm:w-8 sm:h-6 shrink-0"></i>
              <span class="truncate">Grade Reports</span>
            </a>
          </li>
        </ul>
      </div>

    </div>
  </div>

  <div class="flex gap-3 sm:gap-4 items-center justify-between border-t border-[#545878] pt-4 mt-4">
    <div class="flex gap-3 sm:gap-4 items-center min-w-0">
      <div class="bg-[#6366F1] text-white p-2 rounded-full font-semibold shrink-0">
        <span class="text-xs">GS</span>
      </div>

      <div class="min-w-0">
        <p class="text-white text-sm truncate">{{ $user?->name ?? 'Admin' }}</p>
        <p class="gs-secondary-text text-xs truncate">{{ $user?->email ?? 'Administrator' }}</p>
      </div>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
      @csrf
      <button type="submit" title="Logout" aria-label="Logout"
        class="p-1.5 rounded-lg text-red-700 hover:text-red-500 hover:bg-red-500/10 transition cursor-pointer">
        <i data-lucide="log-out" class="w-5 h-5"></i>
      </button>
    </form>
  </div>
</aside>
