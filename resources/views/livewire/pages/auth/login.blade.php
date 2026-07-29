<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = Auth::user();

        if (!$user) {
            $this->redirect(route('login', absolute: false), navigate: false);
            return;
        }

        if ($user->role === 'admin') {
            $this->redirect(route('admin.dashboard', absolute: false), navigate: false);
            return;
        }

        if ($user->role === 'produsen') {
            $this->redirect(route('producer.dashboard', absolute: false), navigate: false);
            return;
        }

        $this->redirect(route('buyer.dashboard', absolute: false), navigate: false);
    }
}; ?>

<div>
    {{-- Header Form --}}
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-extrabold text-gray-900">Masuk ke TaniDirect</h1>
        <p class="mt-2 text-sm text-gray-500">Selamat datang kembali! Silakan masuk ke akun Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
            <input
                wire:model="form.email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@email.com"
                class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-600/20"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs font-semibold text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-green-700 hover:text-green-800" href="{{ route('password.request') }}" wire:navigate>
                        Lupa password?
                    </a>
                @endif
            </div>

            <input
                wire:model="form.password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-600/20"
            />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs font-semibold text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-600"
                    name="remember"
                >
                <span class="ms-2 text-sm font-medium text-gray-600">Ingat Saya</span>
            </label>
        </div>

        <button
            type="submit"
            class="mt-2 w-full rounded-xl bg-green-700 py-3.5 text-sm font-bold text-white shadow-md shadow-green-700/20 transition hover:bg-green-800 active:scale-[0.99]"
        >
            Masuk Sekarang
        </button>
    </form>

    <div class="mt-6 border-t border-gray-100 pt-6 text-center text-xs text-gray-500 space-y-2">
        <p>Pilih jenis akun yang sesuai:</p>
        <div class="flex items-center justify-center gap-4 font-semibold text-green-700">
            <a href="{{ route('buyer.login') }}" class="hover:underline">Login Pembeli</a>
            <span>•</span>
            <a href="{{ route('producer.login') }}" class="hover:underline">Login Petani</a>
        </div>
    </div>
</div>
