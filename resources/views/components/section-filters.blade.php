@props([
    'action',
    'resetUrl',
    'yearLevels',
    'sectionNames',
    'selectedYearLevel' => null,
    'selectedSection' => null,
    'searchPlaceholder' => 'Search...',
])

<form method="GET" action="{{ $action }}" {{ $attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between bg-[#13162A] p-4 rounded-xl border border-[#545878]/30']) }}>
  <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 flex-1 w-full min-w-0">
    <div class="relative w-full sm:w-auto sm:min-w-[130px]">
      <select name="year_level" onchange="this.form.submit()"
        class="w-full appearance-none bg-[#1C2035] border border-[#545878] text-white px-4 pr-10 py-2 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#6366F1] text-sm cursor-pointer">
        <option value="">— Year —</option>
        @foreach($yearLevels as $yl)
          <option value="{{ $yl }}" @selected((string)$selectedYearLevel === (string)$yl)>Grade {{ $yl }}</option>
        @endforeach
      </select>
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#545878]">
        <i data-lucide="chevron-down" class="w-4 h-4"></i>
      </div>
    </div>

    <div class="relative w-full sm:w-auto sm:min-w-[140px]">
      <select name="section" onchange="this.form.submit()"
        class="w-full appearance-none bg-[#1C2035] border border-[#545878] text-white px-4 pr-10 py-2 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#6366F1] text-sm cursor-pointer">
        <option value="">— Section —</option>
        @foreach($sectionNames as $sn)
          <option value="{{ $sn }}" @selected((string)$selectedSection === (string)$sn)>Section {{ $sn }}</option>
        @endforeach
      </select>
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#545878]">
        <i data-lucide="chevron-down" class="w-4 h-4"></i>
      </div>
    </div>

    <div class="flex items-center gap-3 bg-[#1C2035] border border-[#545878] px-4 py-2 rounded-lg hover:border-[#6366F1] focus-within:ring-1 focus-within:ring-[#6366F1] focus-within:border-[#6366F1] transition-all duration-200 w-full sm:flex-1 sm:min-w-[200px] lg:min-w-[280px]">
      <i data-lucide="search" class="text-gray-400 w-4 h-4 shrink-0"></i>
      <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="{{ $searchPlaceholder }}"
        class="w-full min-w-0 bg-transparent text-sm text-white placeholder-gray-500 focus:outline-none">
    </div>
  </div>

  <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
    <button type="submit" class="flex-1 sm:flex-none bg-indigo-500/10 border border-indigo-500/30 hover:bg-indigo-500/20 text-[#8B84FF] px-4 py-2 rounded-lg cursor-pointer text-sm font-semibold transition text-center">
      Filter
    </button>
    <a href="{{ $resetUrl }}" class="flex-1 sm:flex-none text-center bg-[#22273D] border border-[#545878]/30 hover:bg-[#2B304A] text-gray-300 px-4 py-2 rounded-lg text-sm transition">
      Reset
    </a>
  </div>
</form>
