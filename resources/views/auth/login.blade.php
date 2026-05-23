<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Lending System | Access Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body { background: radial-gradient(circle at top left, #1f2937 0%, #101214 45%, #0b0f14 100%); }
    </style>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,#1f2937_0%,#101214_45%,#0b0f14_100%)] text-[#e5e7eb]">
    <div class="min-h-screen px-4 py-8 lg:py-12">
        <div class="mx-auto w-full max-w-xl overflow-hidden border-4 border-[#2b3138] bg-[#171a1f] shadow-[14px_14px_0_0_#0a0c0f]">
            <section class="bg-[#13171c] p-8 lg:p-12">
                <div class="border-2 border-[#374151] bg-[#0d1116] p-6 shadow-[8px_8px_0_0_#090b0d]">
                    <div class="mb-6">
                        <div class="text-xs font-black uppercase tracking-[0.24em] text-[#fbbf24]">Authentication</div>
                        <h2 class="mt-2 text-3xl font-black uppercase text-[#f9fafb]">System Sign In</h2>
                    </div>

                    @if (session('status'))
                        <div class="mb-5 border-2 border-emerald-500/70 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 border-2 border-rose-500/70 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                            <div class="mb-1 font-black uppercase tracking-[0.12em]">Access Denied</div>
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-xs font-black uppercase tracking-[0.14em] text-[#9ca3af]">Operator Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="w-full border-2 border-[#3b4450] bg-[#0b0f14] px-4 py-3 text-sm text-[#f9fafb] outline-none transition focus:border-[#22d3ee] focus:shadow-[0_0_0_3px_rgba(34,211,238,0.15)]"
                                placeholder="name@example.com">
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-xs font-black uppercase tracking-[0.14em] text-[#9ca3af]">Security Key</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="w-full border-2 border-[#3b4450] bg-[#0b0f14] px-4 py-3 text-sm text-[#f9fafb] outline-none transition focus:border-[#f59e0b] focus:shadow-[0_0_0_3px_rgba(245,158,11,0.15)]"
                                placeholder="••••••••">
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <div class="flex items-center gap-2">
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="border-2 border-[#22d3ee] bg-[#0e2730] px-4 py-2 text-xs font-black uppercase tracking-[0.1em] text-[#a5f3fc] hover:bg-[#133341]">
                                        Register
                                    </a>
                                @endif

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-semibold uppercase tracking-[0.1em] text-[#9ca3af] hover:text-[#f3f4f6]">
                                        Forgot Password
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="border-2 border-[#f59e0b] bg-[#f59e0b] px-5 py-2.5 text-xs font-black uppercase tracking-[0.14em] text-[#111827] transition hover:bg-[#fbbf24]">
                                LOG IN
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
