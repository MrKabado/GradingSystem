@extends('layouts.index')

@section('content')
    <div class="relative bg-[#0D0F1A] h-screen flex items-center justify-center">
        <div
            class="bg-[#13162A] text-white min-w-sm flex flex-col justify-center items-center gap-5 py-5 px-6
  shadow-[0_2px_100px_5px_rgba(0,0,0,0.25)] rounded-2xl border border-[#545878]">
            <div class="text-center flex flex-col items-center justify-center">
                <div class="bg-[#6366F1] rounded-md w-fit px-3 py-2">
                    <h1 class="text-lg font-bold">GS</h1>
                </div>

                <h1 class="text-xl font-semibold">GradeSync</h1>
                <p class="text-[#6B7280] text-sm">Administrator Portal</p>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="absolute top-4 mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/auth/login" class="w-full flex flex-col gap-3 mt-2">
                @csrf

                <div>
                    <h1 class="text-xl font-semibold">Sign in</h1>
                    <p class="text-[#6B7280] text-sm">Access the admin dashboard</p>
                </div>

                <div class="flex flex-col text-[#6B7280] gap-1">
                    <label for="email">Email</label>
                    <input type="email" placeholder="juan@email.com" id="email" name="email"
                        class="bg-[#22273D] px-4 py-2 rounded-lg 
                        placeholder-[#6B7280]
                        focus:outline-none
                        focus:ring-1
                        focus:ring-[#545878]"
                        value="{{ old('email') }}">
                </div>

                <div class="flex flex-col text-[#6B7280] gap-1">
                    <label for="password">Password</label>
                    <input type="password" placeholder="******" id="password" name="password"
                        class="bg-[#22273D] px-4 py-2 rounded-lg 
                        placeholder-[#6B7280]
                        focus:outline-none
                        focus:ring-1
                        focus:ring-[#545878]">
                </div>

                <button type="submit" class="bg-[#6366F1] py-2 rounded-2xl mt-2">
                    Login
                </button>

                <p class="text-center text-sm text-[#6B7280]">Forgot Passowrd?
                    <a class="text-[#6366F1]">Reset</a>
                </p>
            </form>
        </div>
    </div>
@endsection
