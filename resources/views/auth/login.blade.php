@extends('layouts.auth')

@section('content')
  <div class="relative gs-primary-bg min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div
      class="gs-card rounded-2xl text-white w-full max-w-sm flex flex-col justify-center items-center gap-5 py-6 sm:py-8 px-5 sm:px-6 shadow-[0_2px_100px_5px_rgba(0,0,0,0.25)]">
      <div class="text-center flex flex-col items-center justify-center">
        <div class="bg-[#6366F1] rounded-md w-fit px-3 py-2">
          <h1 class="text-lg font-bold">GS</h1>
        </div>

        <h1 class="text-xl font-semibold mt-2">GradeSync</h1>
        <p class="text-[#6B7280] text-sm">Administrator Portal</p>
      </div>

      @if ($errors->any())
        <div class="w-full rounded-lg border border-red-400/50 bg-red-500/10 text-red-300 px-4 py-3 text-sm">
          <ul class="space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="/auth/login" class="w-full flex flex-col gap-3 mt-2">
        @csrf

        <div>
          <h2 class="text-xl font-semibold">Sign in</h2>
          <p class="gs-secondary-text text-sm">Access the admin dashboard</p>
        </div>

        <div class="flex flex-col text-[#6B7280] gap-1">
          <label for="email">Email</label>
          <input type="email" placeholder="juan@email.com" id="email" name="email" required
            class="w-full bg-[#22273D] px-4 py-2.5 rounded-lg placeholder-[#6B7280] text-white focus:outline-none focus:ring-1 focus:ring-[#6366F1]"
            value="{{ old('email') }}">
        </div>

        <div class="flex flex-col text-[#6B7280] gap-1">
          <label for="password">Password</label>
          <input type="password" placeholder="******" id="password" name="password" required
            class="w-full bg-[#22273D] px-4 py-2.5 rounded-lg placeholder-[#6B7280] text-white focus:outline-none focus:ring-1 focus:ring-[#6366F1]">
        </div>

        <button type="submit" class="w-full bg-[#6366F1] hover:bg-[#5558e8] py-2.5 rounded-2xl mt-2 font-semibold transition">
          Login
        </button>
      </form>
    </div>
  </div>
@endsection
