<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\AsCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

// Comando utilitário para criar rapidamente contas administrativas via CLI.
#[AsCommand(name: 'admin:create', description: 'Cria um usuário administrador para o sistema')]
class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
        {name? : Nome do administrador}
        {email? : Endereço de e-mail}
        {password? : Senha (deixe em branco para informar via prompt)}';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $data = [
            'name' => $this->argument('name') ?? $this->ask('Nome completo'),
            'email' => $this->argument('email') ?? $this->ask('E-mail'),
        ];

        $passwordArgument = $this->argument('password');

        if ($passwordArgument === null) {
            $data['password'] = $this->secret('Senha (não será exibida)');
            $data['password_confirmation'] = $this->secret('Confirme a senha');
        } else {
            $data['password'] = $passwordArgument;
            $data['password_confirmation'] = $passwordArgument;
        }

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_admin' => true,
        ]);

        $this->info("Usuário administrador criado com sucesso (ID {$user->id}).");

        return self::SUCCESS;
    }
}
