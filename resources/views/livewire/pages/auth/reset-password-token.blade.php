<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $this->email)->first();

        if (!$resetRecord || !Hash::check($this->token, $resetRecord->token)) {
            $this->addError('token', 'Token tidak valid atau email salah.');
            return;
        }

        // Validasi kadaluarsa 1 jam
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addHour()->isPast()) {
            $this->addError('token', 'Token sudah kadaluarsa. Silakan minta token baru ke Admin.');
            return;
        }

        $user = User::where('email', $this->email)->first();
        if (!$user) {
            $this->addError('email', 'User tidak ditemukan.');
            return;
        }

        $user->forceFill([
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        event(new PasswordReset($user));

        Session::flash('status', 'Password berhasil direset! Silakan login.');

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Masukkan token yang diberikan oleh Admin beserta email dan password baru Anda.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="resetPassword">
        <!-- Token -->
        <div>
            <x-input-label for="token" value="Token Reset" />
            <x-text-input wire:model="token" id="token" class="block mt-1 w-full font-mono text-center tracking-widest" type="text" name="token" required autofocus autocomplete="off" />
            <x-input-error :messages="$errors->get('token')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password Baru" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</div>
