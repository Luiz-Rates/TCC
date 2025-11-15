<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\SaleItem;
use App\Models\Sale;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function vendas(Request $request)
    {
        $mesInicio = $request->input('mes_inicio');
        $mesFim = $request->input('mes_fim');
        $inicioCarbon = null;
        $fimCarbon = null;
        $erroPeriodo = null;
        $limitePassado = null;
        $limitePassadoMes = null;

        $primeiraVenda = Sale::where('user_id', $request->user()->id)->min('data');
        if ($primeiraVenda) {
            $limitePassado = Carbon::parse($primeiraVenda)->startOfMonth();
        } else {
            $limitePassado = now()->subYear()->startOfMonth();
        }
        $limitePassadoMes = $limitePassado->format('Y-m');

        try {
            if ($mesInicio) {
                $inicioCarbon = Carbon::createFromFormat('Y-m', $mesInicio)->startOfMonth();
            }

            if ($mesFim) {
                $fimCarbon = Carbon::createFromFormat('Y-m', $mesFim)->endOfMonth();
            }

            if ($inicioCarbon && ! $fimCarbon) {
                $fimCarbon = $inicioCarbon->copy()->endOfMonth();
                $mesFim = $fimCarbon->format('Y-m');
            }

            if ($fimCarbon && ! $inicioCarbon) {
                $inicioCarbon = $fimCarbon->copy()->startOfMonth();
                $mesInicio = $inicioCarbon->format('Y-m');
            }
        } catch (\Exception $e) {
            $inicioCarbon = $fimCarbon = null;
            $mesInicio = $mesFim = null;
        }

        if (! $erroPeriodo && $inicioCarbon && $inicioCarbon->lt($limitePassado)) {
            $erroPeriodo = 'O mês inicial não pode ser anterior a ' . $limitePassado->format('m/Y') . '.';
            $inicioCarbon = $fimCarbon = null;
        }

        if (! $erroPeriodo && $fimCarbon && $fimCarbon->lt($limitePassado)) {
            $erroPeriodo = 'O mês final não pode ser anterior a ' . $limitePassado->format('m/Y') . '.';
            $inicioCarbon = $fimCarbon = null;
        }

        if ($inicioCarbon && $fimCarbon && $inicioCarbon->gt($fimCarbon)) {
            $erroPeriodo = 'O mês inicial não pode ser maior que o mês final.';
            $inicioCarbon = $fimCarbon = null;
        }

        $dataInicio = $inicioCarbon?->toDateString();
        $dataFim = $fimCarbon?->toDateString();
        $total = 0;
        $quantidade = 0;
        $totalPagas = 0;
        $totalEmAberto = 0;
        $media = null;
        $dadosGrafico = [];
        $colecaoCompleta = collect();

        if ($erroPeriodo) {
            $vendas = SaleItem::query()
                ->whereRaw('0 = 1')
                ->paginate(20)
                ->withQueryString();
        } else {
            $query = SaleItem::with(['sale.client', 'product'])
                ->whereHas('sale', function($q) use ($request, $dataInicio, $dataFim) {
                    $q->where('user_id', $request->user()->id);
                    if ($dataInicio && $dataFim) {
                        $q->whereBetween('data', [$dataInicio, $dataFim]);
                    }
                })
                ->orderByDesc('sale_items.created_at');

            $mapVenda = function ($item) {
                $item->data = $item->sale->data;
                $item->cliente = $item->sale->client;
                $item->produto = $item->product;
                $item->total = $item->subtotal;
                $item->fiado = $item->sale->status === 'fiado';
                return $item;
            };

            $colecaoCompleta = (clone $query)->get()->map($mapVenda);

            $vendas = $query->paginate(20)->withQueryString();
            $vendas->getCollection()->transform($mapVenda);

            $total = $colecaoCompleta->sum('subtotal');
            $quantidade = $colecaoCompleta->count();

            $totalPagas = $colecaoCompleta->where('fiado', false)->sum('subtotal');
            $totalEmAberto = $colecaoCompleta->where('fiado', true)->sum('subtotal');

            if ($inicioCarbon && $fimCarbon) {
                $days = $inicioCarbon->diffInDays($fimCarbon) + 1;
                $media = $days > 0 ? $total / $days : 0;
            }

            if ($inicioCarbon && $fimCarbon) {
                $current = $inicioCarbon->copy();
                while ($current->lte($fimCarbon)) {
                    $monthKey = $current->format('m/Y');
                    $sum = $colecaoCompleta->filter(function($venda) use ($current) {
                        $saleDate = Carbon::parse($venda->data);
                        return $saleDate->month === $current->month && $saleDate->year === $current->year;
                    })->sum('total');
                    $dadosGrafico[$monthKey] = $sum;
                    $current->addMonth();
                }
            }
        }

        return view('relatorios.vendas', compact(
            'total',
            'quantidade',
            'dataInicio',
            'dataFim',
            'mesInicio',
            'mesFim',
            'vendas',
            'totalPagas',
            'totalEmAberto',
            'media',
            'dadosGrafico',
            'erroPeriodo',
            'limitePassadoMes'
        ));
    }
    public function fiados(Request $request)
    {
        // Retorna os clientes que possuem vendas em aberto para o usuário logado.
        $clientes = Client::where('user_id', $request->user()->id)
            ->whereHas('sales', function ($q) use ($request) {
                $q->where('status', 'fiado')
                    ->where('user_id', $request->user()->id);
            })
            ->with(['sales' => function ($q) use ($request) {
                $q->where('status', 'fiado')
                    ->where('user_id', $request->user()->id);
            }])
            ->orderBy('nome')
            ->get();

        return view('relatorios.fiados', compact('clientes'));
    }

    public function fiadosPorCliente(Request $request, Client $cliente)
    {
        if ($cliente->user_id !== $request->user()->id) {
            abort(403);
        }

        $vendas = $cliente->sales()
            ->where('status', 'fiado')
            ->where('user_id', $request->user()->id)
            ->get();

        return view('relatorios.fiados_cliente', compact('cliente', 'vendas'));
    }
}
