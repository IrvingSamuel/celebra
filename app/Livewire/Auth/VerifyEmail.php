<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public bool $resent = false;

    public function resend(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirect('/dashboard', navigate: true);
            return;
        }

        $user->sendEmailVerificationNotification();
        $this->resent = true;
    }

    public function render()
    {
        $user = Auth::user();

        if ($user?->hasVerifiedEmail()) {
            return $this->redirect('/dashboard', navigate: true);
        }

        return view('livewire.auth.verify-email');
    }
}
