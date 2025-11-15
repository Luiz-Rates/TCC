<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Controla o CRUD de usuários administrativos do sistema.
 *
 * Cada método possui validações extras para evitar perda de contas críticas
 * e garantir que apenas administradores gerenciem outros usuários.
 */
class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        // Lista usuários do sistema com destaque para administradores.

        $users = User::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search->value(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_admin' => ['sometimes', 'boolean'],
        ]);

        // Cria o usuário com hash automático via cast.
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_admin' => ['sometimes', 'boolean'],
        ]);

        // Impede que o usuário remova o próprio privilégio de admin.
        if ($request->user()->is($user) && ! $request->boolean('is_admin')) {
            return back()
                ->withInput()
                ->with('error', 'Você não pode remover seu próprio acesso de administrador.');
        }

        if (! $request->boolean('is_admin') && $user->is_admin && $this->isLastAdmin($user)) {
            return back()
                ->withInput()
                ->with('error', 'Mantenha ao menos um administrador ativo no sistema.');
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Dados do usuário atualizados com sucesso.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Você não pode remover a própria conta por aqui.');
        }

        if ($user->is_admin && $this->isLastAdmin($user)) {
            return back()->with('error', 'Mantenha ao menos um administrador ativo no sistema.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário removido com sucesso.');
    }

    /**
     * Check if the given user is the last administrator.
     */
    protected function isLastAdmin(User $user): bool
    {
        return User::where('is_admin', true)
            ->where('id', '!=', $user->id)
            ->doesntExist();
    }
}
