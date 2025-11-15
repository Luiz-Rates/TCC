<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

/**
 * Controla o fluxo de vendas e sincroniza estoque de produtos do usuário.
 */
class SaleController extends Controller
{
    /**
     * Lista vendas com filtros simples por cliente, mês e status.
     */
    public function index(Request $request)
    {
        $query = Sale::with('client')
            ->where('user_id', $request->user()->id);

        if ($request->filled('cliente')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        $vendas = $query->orderBy('data', 'desc')->paginate(20);

        return view('vendas.index', compact('vendas', 'request'));
    }

    public function show(Request $request, Sale $venda)
    {
        $this->ensureSaleOwnership($request, $venda);

        $venda->load(['client', 'items.product']);
        return view('vendas.show', compact('venda'));
    }

    public function create(Request $request)
    {
        $clientes = Client::where('user_id', $request->user()->id)
            ->orderBy('nome')
            ->get();
        $produtos = $this->productsForUser($request->user()->id);
        return view('vendas.create', compact('clientes', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where('user_id', $request->user()->id),
            ],
            'data' => 'required|date',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantidades' => 'required|array',
            'quantidades.*' => 'required|integer|min:1',
            'status' => 'required|in:pago,fiado',
        ]);

        try {
            $total = 0;
            $items = [];

            $userId = $request->user()->id;

            $produtosSemEstoque = [];

            foreach ($request->products as $index => $productId) {
                $produto = $this->productForUserOrFail($productId, $userId);
                $quantidade = $request->quantidades[$index];

                if ($produto->quantidade < $quantidade) {
                    throw ValidationException::withMessages([
                        "quantidades.$index" => "Quantidade solicitada para {$produto->nome} excede o estoque disponível ({$produto->quantidade}).",
                    ]);
                }

                $subtotal = $produto->preco * $quantidade;
                $total += $subtotal;

                $items[] = [
                    'product_id' => $produto->id,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $produto->preco,
                    'subtotal' => $subtotal,
                ];

                // Atualizar estoque
                $produto->quantidade -= $quantidade;
                $produto->save();

                if ((int) $produto->quantidade === 0) {
                    $produtosSemEstoque[] = $produto->nome;
                }
            }

            $venda = Sale::create([
                'client_id' => $request->client_id,
                'data' => $request->data,
                'total_geral' => $total,
                'status' => $request->status,
                'user_id' => $request->user()->id,
            ]);

            foreach ($items as $item) {
                $item['sale_id'] = $venda->id;
                SaleItem::create($item);
            }

            $redirectTo = $request->input('redirect_to');

            $response = $redirectTo
                ? redirect($redirectTo)
                : redirect()->route('vendas.index');

            $response = $response->with('success', 'Venda registrada com sucesso!');

            if ($produtosSemEstoque) {
                $nomes = implode(', ', $produtosSemEstoque);
                $response = $response->with('warning', "Estoque zerado para: {$nomes}.");
            }

            return $response;
        } catch (\Throwable $th) {
            report($th);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível registrar a venda. Tente novamente.');
        }
    }

    public function edit(Request $request, Sale $venda)
    {
        $this->ensureSaleOwnership($request, $venda);

        $venda->load(['items']);
        $clientes = Client::where('user_id', $request->user()->id)
            ->orderBy('nome')
            ->get();
        $produtos = $this->productsForUser($request->user()->id);
        return view('vendas.edit', compact('venda', 'clientes', 'produtos'));
    }

    public function destroy(Request $request, Sale $venda)
    {
        $this->ensureSaleOwnership($request, $venda);

        try {
            $userId = $request->user()->id;
            // Restaurar estoque dos itens da venda
            foreach ($venda->items as $item) {
                $produto = $this->productForUser($item->product_id, $userId);
                if ($produto) {
                    $produto->quantidade += $item->quantidade;
                    $produto->save();
                }
            }

            $venda->delete();
            $redirectTo = $request->input('redirect_to');

            return $redirectTo
                ? redirect($redirectTo)->with('success', 'Venda excluída com sucesso!')
                : redirect()->route('vendas.index')->with('success', 'Venda excluída com sucesso!');
        } catch (\Throwable $th) {
            report($th);

            return back()->with('error', 'Não foi possível excluir a venda. Tente novamente.');
        }
    }

    public function update(Request $request, Sale $venda)
    {
        $this->ensureSaleOwnership($request, $venda);

        $request->validate([
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where('user_id', $request->user()->id),
            ],
            'data' => 'required|date',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantidades' => 'required|array',
            'quantidades.*' => 'required|integer|min:1',
            'status' => 'required|in:pago,fiado',
        ]);

        try {
            // Restaurar estoque dos itens antigos
            $userId = $request->user()->id;

            foreach ($venda->items as $item) {
                $produto = $this->productForUser($item->product_id, $userId);
                if ($produto) {
                    $produto->quantidade += $item->quantidade;
                    $produto->save();
                }
            }

            $total = 0;
            $items = [];
            $produtosSemEstoque = [];

            foreach ($request->products as $index => $productId) {
                $produto = $this->productForUserOrFail($productId, $userId);
                $quantidade = $request->quantidades[$index];
                if ($produto->quantidade < $quantidade) {
                    throw ValidationException::withMessages([
                        "quantidades.$index" => "Quantidade solicitada para {$produto->nome} excede o estoque disponível ({$produto->quantidade}).",
                    ]);
                }
                $subtotal = $produto->preco * $quantidade;
                $total += $subtotal;

                $items[] = [
                    'product_id' => $produto->id,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $produto->preco,
                    'subtotal' => $subtotal,
                ];

                // Decrementar estoque
                $produto->quantidade -= $quantidade;
                $produto->save();

                if ((int) $produto->quantidade === 0) {
                    $produtosSemEstoque[] = $produto->nome;
                }
            }

            // Atualizar dados da venda
            $venda->update([
                'client_id' => $request->client_id,
                'data' => $request->data,
                'total_geral' => $total,
                'status' => $request->status,
            ]);

            // Remover itens antigos
            $venda->items()->delete();

            // Criar novos itens
            foreach ($items as $item) {
                $item['sale_id'] = $venda->id;
                SaleItem::create($item);
            }

            $redirectTo = $request->input('redirect_to');

            $response = $redirectTo
                ? redirect($redirectTo)
                : redirect()->route('vendas.index');

            $response = $response->with('success', 'Venda atualizada com sucesso!');

            if ($produtosSemEstoque) {
                $nomes = implode(', ', $produtosSemEstoque);
                $response = $response->with('warning', "Estoque zerado para: {$nomes}.");
            }

            return $response;
        } catch (\Throwable $th) {
            report($th);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar a venda. Tente novamente.');
        }
    }

    /**
     * Lista os produtos do usuário autenticado.
     */
    protected function productsForUser(int $userId)
    {
        return Product::where('user_id', $userId)
            ->orderBy('nome')
            ->get();
    }

    /**
     * Obtém um produto do usuário ou falha.
     */
    protected function productForUserOrFail(int $productId, int $userId): Product
    {
        return Product::where('user_id', $userId)->findOrFail($productId);
    }

    /**
     * Obtém um produto do usuário ou retorna nulo.
     */
    protected function productForUser(int $productId, int $userId): ?Product
    {
        return Product::where('user_id', $userId)->find($productId);
    }

    private function ensureSaleOwnership(Request $request, Sale $venda): void
    {
        if ($venda->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
