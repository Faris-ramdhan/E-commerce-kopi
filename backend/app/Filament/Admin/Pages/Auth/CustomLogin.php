<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    protected string $view = 'filament.admin.auth.login';

    // Gunakan layout base kosong agar tidak terbungkus container sempit fi-simple-main
    protected static string $layout = 'filament-panels::components.layout.base';

    public function getHeading(): string|Htmlable
    {
        return 'Masuk ke Dashboard';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Masukkan email dan kata sandi operasional Anda.';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat Email')
            ->placeholder('nama@kopi.test')
            ->email()
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->placeholder('••••••••')
            ->password()
            ->revealable()
            ->autocomplete('current-password')
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya');
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'Email atau kata sandi tidak sesuai. Silakan coba lagi.',
        ]);
    }
}
