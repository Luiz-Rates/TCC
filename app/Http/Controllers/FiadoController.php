<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Gestão das vendas em aberto (antigo fiado).
 */
class FiadoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $vendasQuery = Sale::with(['client', 'items.product'])
            ->where('status', 'fiado')
            ->where('user_id', $request->user()->id);

        if ($search !== '') {
            $searchLike = '%' . $search . '%';

            $searchDate = null;
            foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $format) {
                try {
                    $searchDate = Carbon::createFromFormat($format, $search)->format('Y-m-d');
                    break;
                } catch (\Throwable $th) {
                    continue;
                }
            }

            $vendasQuery->where(function ($query) use ($searchLike, $searchDate) {
                $query->whereHas('client', function ($clientQuery) use ($searchLike) {
                    $clientQuery->where('nome', 'like', $searchLike);
                })
                ->orWhereHas('items.product', function ($productQuery) use ($searchLike) {
                    $productQuery->where('nome', 'like', $searchLike);
                });

                if ($searchDate) {
                    $query->orWhereDate('data', $searchDate);
                }
            });
        }

        $vendas = $vendasQuery
            ->orderByDesc('data')
            ->paginate(20)
            ->withQueryString();

        return view('fiados.index', [
            'vendas' => $vendas,
            'search' => $search,
        ]);
    }

    public function receber(Request $request, $id)
    {
        try {
            $venda = Sale::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
            $venda->status = 'pago';
            $venda->save();

            $redirectTo = $request->input('redirect_to');

            return $redirectTo
                ? redirect($redirectTo)->with('success', 'Venda marcada como paga!')
                : redirect()->route('fiados.index')->with('success', 'Venda marcada como paga!');
        } catch (\Throwable $th) {
            report($th);

            return back()->with('error', 'Não foi possível marcar a venda como paga. Tente novamente.');
        }
    }
}
