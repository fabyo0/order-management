<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Str;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class RegisterPasswords extends Component
{
    public ?string $password;

    public ?string $passwordConfirmation;

    public int $strengthScore = 0;

    public array $strengthLevels = [
        1 => 'Weak',
        2 => 'Fair',
        3 => 'Good',
        4 => 'Strong',
    ];

    // Generate password random
    public function generatePassword(): void
    {
        $password = Str::password(12);
        $this->setPasswords($password);
    }

    protected function setPasswords($value): void
    {
        $this->password = $value;
        $this->passwordConfirmation = $value;
        $this->updatedPassword($value);
    }

    // Checked the password strength
    public function updatedPassword($value): void
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
    }


    public function render()
    {
        return view('livewire.auth.register-passwords');
    }
}
