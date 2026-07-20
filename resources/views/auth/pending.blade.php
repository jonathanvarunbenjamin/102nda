<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <h1 class="mt-4 text-lg font-semibold text-gray-900">Awaiting approval</h1>

        <p class="mt-2 text-sm text-gray-600">
            Thanks for signing in, {{ Auth::user()->name }}.
            Your account is waiting for an administrator to confirm you as a member
            of the 102nd NDA course. You'll get full access as soon as you're approved.
        </p>

        <p class="mt-2 text-xs text-gray-500">
            Signed in as {{ Auth::user()->email }}
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="text-sm text-indigo-600 underline hover:text-indigo-800">
                Sign out
            </button>
        </form>
    </div>
</x-guest-layout>
