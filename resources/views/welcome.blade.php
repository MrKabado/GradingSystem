@extends('layouts.auth')

@section('content')
  <section class="gs-primary-bg text-center pt-28 pb-20 px-6 h-screen">

    <div
      class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
        bg-[#1c2235] border border-[#252d40]
        text-xs text-[#8892a4] mb-6">

      <span class="w-2 h-2 rounded-full bg-green-400"></span>
      School Management Platform
    </div>

    <h1 class="text-white font-bold text-6xl leading-tight max-w-4xl mx-auto mb-6">
      Manage Grades.<br>
      <span class="text-[#4f6ef7]">Stay in Sync.</span>
    </h1>

    <p class="text-[#8892a4] text-lg max-w-2xl mx-auto mb-10">
      GradeSync gives administrators and teachers a unified portal
      to track students and academic performance.
    </p>

    <div class="flex justify-center gap-4">
      <a href="{{ route('login') }}" class="gs-primary-btn">
        Get Started
      </a>

      <a href="{{ route('login') }}" class="gs-secondary-btn">
        Log in
      </a>
    </div>

  </section>
@endsection
