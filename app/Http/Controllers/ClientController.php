<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $clientes = Client::query()
            ->where('user_id', $request->user()->id)
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';

                $query->where(function ($subQuery) use ($like) {
                    $subQuery->where('nome', 'like', $like)
                        ->orWhere('telefone', 'like', $like)
                        ->orWhere('email', 'like', $like);
                });
            })
            ->orderBy('nome')
            ->paginate(20)
            ->withQueryString();

        return view('clientes.index', [
            'clientes' => $clientes,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'endereco' => 'nullable|string',
        ]);

        $dados['user_id'] = $request->user()->id;

        try {
            Client::create($dados);

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível cadastrar o cliente. Tente novamente.');
        }
    }

    public function edit(Request $request, Client $cliente)
    {
        $this->ensureOwnership($request, $cliente);

        return view('clientes.edit', compact('cliente'));
    }

    public function show(Request $request, Client $cliente)
    {
        $this->ensureOwnership($request, $cliente);

        $cliente->load([
            'sales' => function ($query) {
                // Carrega vendas com produtos para histórico detalhado.
                $query->with(['items.product'])->orderByDesc('data');
            },
        ]);

        return view('clientes.show', compact('cliente'));
    }

    public function update(Request $request, Client $cliente)
    {
        $this->ensureOwnership($request, $cliente);

        $dados = $request->validate([
            'nome' => 'required|string',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'endereco' => 'nullable|string',
        ]);

        try {
            $cliente->update($dados);

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar o cliente. Tente novamente.');
        }
    }

    public function destroy(Request $request, Client $cliente)
    {
        $this->ensureOwnership($request, $cliente);

        try {
            $cliente->delete();

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente removido com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()->with('error', 'Não foi possível remover o cliente. Tente novamente.');
        }
    }

    private function ensureOwnership(Request $request, Client $cliente): void
    {
        if ($cliente->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
