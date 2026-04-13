<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'client';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:client,supplier',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas não conferem.',
        ];
    }

    public function register()
    {
        $this->validate();

        $base = Str::slug($this->name);
        $slug = $base;
        $counter = 1;
        while (User::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }

        $user = User::create([
            'name'  => $this->name,
            'slug'  => $slug,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
