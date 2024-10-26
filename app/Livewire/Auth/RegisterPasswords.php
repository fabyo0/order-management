<?php

namespace App\Livewire\Auth;

use App\enums\PasswordStrength;
use Illuminate\Support\Str;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class RegisterPasswords extends Component
{
    public ?string $password;

    public ?string $passwordConfirmation;

    public int $strengthScore = 0;

    public ?array $strengthLevels;

    // Generate password random
    public function generatePassword(): void
    {
        $password = Str::password(12);
        $this->setPasswords($password);
    }

    public function mount()
    {
        $this->strengthLevels = PasswordStrength::toArray();
    }

    protected function setPasswords($value): void
    {
        $this->password = $value;
        $this->passwordConfirmation = $value;
        $this->calculatePasswordStrength($value);
    }

    // Checked the password strength
    public function calculatePasswordStrength($value): void
    {
        $this->strengthScore = (new Zxcvbn)->passwordStrength($value)['score'];
    }

    public function updatePassword(): void
    {
        $this->calculatePasswordStrength($this->password);
    }

    protected function debouncedPasswordStrengthCalculation(string $password): void
    {
        $this->dispatch('debounce', [
            'fn' => 'calculatePasswordStrength',
            'args' => [$password],
            'delay' => 500,
        ]);
    }

    public function render()
    {
        return view('livewire.auth.register-passwords');
    }
}
