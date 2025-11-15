<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Lista produtos pertencentes ao usuário autenticado.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $query = Product::query()
            ->where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('descricao', 'like', '%' . $search . '%');
            });
        }

        $produtos = $query->orderBy('nome')->paginate(20)->withQueryString();

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    /**
     * Persiste um produto escopado ao usuário logado.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $dados['foto'] = $request->file('foto')->store('produtos', 'public');
            }

            Product::create(array_merge(
                $dados,
                ['user_id' => $request->user()->id]
            ));

            return redirect()
                ->route('produtos.index')
                ->with('success', 'Produto criado com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível criar o produto. Tente novamente.');
        }
    }

    public function edit(Request $request, Product $produto)
    {
        $produto = $this->ensureProductOwnership($request, $produto);

        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Product $produto)
    {
        $dados = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $produto = $this->ensureProductOwnership($request, $produto);

        try {
            if ($request->hasFile('foto')) {
                if ($produto->foto) {
                    Storage::disk('public')->delete($produto->foto);
                }
                $dados['foto'] = $request->file('foto')->store('produtos', 'public');
            }

            $produto->update($dados);

            return redirect()
                ->route('produtos.index')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar o produto. Tente novamente.');
        }
    }

    public function destroy(Request $request, Product $produto)
    {
        $produto = $this->ensureProductOwnership($request, $produto);

        try {
            if ($produto->foto) {
                Storage::disk('public')->delete($produto->foto);
            }

            $produto->delete();

            return redirect()
                ->route('produtos.index')
                ->with('success', 'Produto excluído com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()->with('error', 'Não foi possível excluir o produto. Tente novamente.');
        }
    }

    /**
     * Garante que o produto pertence ao usuário autenticado.
     */
    protected function ensureProductOwnership(Request $request, Product $produto): Product
    {
        if ($produto->user_id !== $request->user()->id) {
            abort(404);
        }

        return $produto;
    }
}
