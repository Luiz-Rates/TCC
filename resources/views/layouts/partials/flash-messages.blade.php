@php
    $hasFlash = session()->has('success')
        || session()->has('error')
        || session()->has('warning')
        || session()->has('info')
        || session()->has('status')
        || session()->has('message')
        || $errors->any();

    $statusKey = session('status');
    $statusMessages = [
        'profile-updated' => 'Perfil atualizado com sucesso!',
        'password-updated' => 'Senha atualizada com sucesso!',
        'two-factor-authentication-enabled' => 'Autenticação de dois fatores ativada com sucesso!',
        'two-factor-authentication-disabled' => 'Autenticação de dois fatores desativada.',
        'recovery-codes-generated' => 'Novos códigos de recuperação foram gerados.',
        'verification-link-sent' => 'Enviamos um novo link de verificação para o seu e-mail.',
    ];
@endphp

@if ($hasFlash)
    <div class="mx-auto mb-6 max-w-7xl space-y-3">
        @if (session()->has('success'))
            <x-flash-message type="success" :message="session('success')" />
        @endif

        @if (session()->has('warning'))
            <x-flash-message type="warning" :message="session('warning')" />
        @endif

        @if (session()->has('info'))
            <x-flash-message type="info" :message="session('info')" />
        @endif

        @if (session()->has('error'))
            <x-flash-message type="error" :message="session('error')" :auto-close="false" />
        @endif

        @if ($statusKey && ! session()->has('success'))
            <x-flash-message type="success" :message="$statusMessages[$statusKey] ?? __($statusKey)" />
        @endif

        @if ($errors->any())
            <x-flash-message
                type="error"
                message="Ops! Encontramos alguns problemas com as informações enviadas."
                :items="$errors->all()"
                :auto-close="false"
            />
        @endif
    </div>
@endif
