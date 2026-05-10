<div
  class="fixed top-0 left-0 h-screen w-64 p-5 gs-secondary-bg flex flex-col justify-between border-r border-[#545878] overflow-y-auto">
  <div>
    <div class="relative border-b-[0.5px] pb-8 border-[#545878]">
      <div class="flex items-center gap-3">
        <div class="flex justify-center px-3 py-2 bg-[#6366F1] rounded-lg">
          <i data-lucide="layers" class="text-white"></i>
        </div>
        <h1 class="text-white text-xl font-semibold">GRADESYNC</h1>
      </div>
      <p class="absolute top-10 left-16 gs-secondary-text text-sm">Admin portal</p>
    </div>

    <div class="space-y-10 mt-10">
      <div>
        <h1 class="text-xs gs-secondary-text mb-2 ml-4">MAIN</h1>
        <div>
          <ul>
            <li>
              <a href="{{ route('dashboard') }}"
                class="w-full gs-secondary-text gs-sidebar-hover-active py-2 px-3 text-lg mb-1 flex gap-2 items-center">
                <i data-lucide="box" class="w-8 h-6"></i>
                Overview
              </a>
            </li>

            <li>
              <a href="{{ route('students.index') }}"
                class="w-full gs-secondary-text gs-sidebar-hover-active py-2 px-3 text-lg mb-1 flex gap-2 items-center">
                <i data-lucide="circle-user-round" class="w-8 h-6"></i>
                Students
              </a>
            </li>

            <li>
              <a href="{{ route('subjects.index') }}"
                class="w-full gs-secondary-text gs-sidebar-hover-active py-2 px-3 text-lg mb-1 flex gap-2 items-center">
                <i data-lucide="book-copy" class="w-8 h-6"></i>
                Subjects
              </a>
            </li>

            <li>
              <a href="{{ route('grades.index') }}"
                class="w-full gs-secondary-text gs-sidebar-hover-active py-2 px-3 text-lg mb-1 flex gap-2 items-center">
                <i data-lucide="chart-no-axes-column" class="w-8 h-6"></i>
                Grades
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div>
        <h1 class="text-xs gs-secondary-text mb-3 ml-4">REPORTS</h1>
        <div>
          <ul>
            <li class="gs-secondary-text gs-sidebar-hover-active py-2 px-3 text-lg mb-1 flex gap-2 items-center">
              <i data-lucide="notepad-text" class="w-8 h-6"></i>
              Grade Reports
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="flex gap-4 items-center border-t border-[#545878] pt-4">
    <div class="bg-[#6366F1] text-white p-3 rounded-full font-semibold">
      <h1>AD</h1>
    </div>

    <div>
      <h1 class="text-white text-sm">Admin</h1>
      <p class="gs-secondary-text text-xs">Administrator</p>
    </div>
  </div>
</div>
