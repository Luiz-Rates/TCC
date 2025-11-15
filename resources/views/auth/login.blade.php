<x-login-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="space-y-2">
            <x-input-label for="email" class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400" :value="__('E-mail')" />
            <x-text-input
                id="email"
                class="block w-full rounded-2xl border border-slate-800/70 bg-slate-900/70 px-4 py-3 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <x-input-label for="password" class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400" :value="__('Senha')" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-200 transition hover:text-blue-100">
                        {{ __('Esqueci a senha') }}
                    </a>
                @endif
            </div>

            <div class="relative" x-data="{ showPassword: false }">
                <x-text-input
                    id="password"
                    class="block w-full rounded-2xl border border-slate-800/70 bg-slate-900/70 px-4 py-3 pr-24 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                    type="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-4 flex items-center text-[0.65rem] font-semibold uppercase tracking-[0.25em] text-blue-200 transition hover:text-blue-100"
                    @click="showPassword = !showPassword"
                    x-bind:aria-pressed="showPassword.toString()"
                    aria-controls="password"
                    x-bind:title="showPassword ? '{{ __('Ocultar senha') }}' : '{{ __('Mostrar senha') }}'"
                >
                    <span x-text="showPassword ? '{{ __('Ocultar') }}' : '{{ __('Mostrar') }}'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-300">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-700 bg-slate-900/80 text-blue-500 focus:ring-blue-500/60 focus:ring-offset-0"
                    name="remember"
                >
                <span>{{ __('Manter conectado') }}</span>
            </label>

            <x-primary-button class="rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-2.5 text-sm font-semibold uppercase tracking-[0.25em] text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
    </form>
</x-login-layout>
