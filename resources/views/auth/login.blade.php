<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- CAPTCHA -->
        <div class="mt-4">
            <x-input-label value="Captcha" />

            <div class="flex items-center gap-3 mt-2">
                <span id="captcha-img">
                    {!! captcha_img() !!}
                </span>

                <button
                    type="button"
                    onclick="refreshCaptcha()"
                    class="px-3 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300"
                >
                    ↻
                </button>
            </div>

            <input
                type="text"
                name="captcha"
                class="block w-full mt-3 border-gray-300 rounded-md shadow-sm"
                placeholder="Enter captcha"
                required
            />

            <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                >
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                    href="{{ route('password.request') }}"
                >
                    Forgot your password?
                </a>
            @endif

            <x-primary-button class="ml-3">
                Log in
            </x-primary-button>
        </div>
    </form>

    <!-- CAPTCHA Refresh Script -->
    <script>
        function refreshCaptcha() {
            fetch('/captcha-refresh')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('captcha-img').innerHTML = data.captcha;
                });
        }
    </script>
</x-guest-layout>
