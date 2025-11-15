<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SampleDataSeeder extends Seeder
{
    /**
     * Executa o seeder populando produtos, clientes e vendas fictícias.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $emailAdministrador = env('SEED_USER_EMAIL', 'admin@nexkeep.local');

        $user = User::firstWhere('email', $emailAdministrador);

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Administrador Sistema de Vendas',
                'email' => $emailAdministrador,
                'password' => bcrypt('senhaSegura123'),
            ]);
        }

        $produtos = collect();

        for ($i = 1; $i <= 50; $i++) {
            $nome = ucfirst($faker->unique()->words($faker->numberBetween(1, 3), true));

            $produtos->push(
                Product::create([
                    'nome' => $nome,
                    'descricao' => $faker->sentence(12),
                    'preco' => round($faker->randomFloat(2, 9.9, 499.9), 2),
                    'quantidade' => $faker->numberBetween(20, 160),
                    'foto' => null,
                    'user_id' => $user->id,
                ])
            );
        }

        $clientes = collect();

        for ($i = 1; $i <= 50; $i++) {
            $clientes->push(
                Client::create([
                    'nome' => $faker->unique()->name(),
                    'telefone' => $faker->cellphoneNumber(),
                    'email' => $faker->unique()->safeEmail(),
                    'endereco' => $faker->streetAddress() . ', ' . $faker->city() . ' - ' . $faker->stateAbbr(),
                    'user_id' => $user->id,
                ])
            );
        }

        $statusPool = array_merge(array_fill(0, 25, 'pago'), array_fill(0, 25, 'fiado'));
        shuffle($statusPool);

        for ($i = 0; $i < 50; $i++) {
            /** @var Collection<int, Product> $produtosDisponiveis */
            $produtosDisponiveis = $produtos->filter(fn (Product $produto) => $produto->quantidade > 0);

            if ($produtosDisponiveis->isEmpty()) {
                break;
            }

            $itensDaVenda = [];
            $total = 0;

            $itensDesejados = min(random_int(1, 4), $produtosDisponiveis->count());
            $selecionados = $produtosDisponiveis->random($itensDesejados);
            $selecionados = $selecionados instanceof Collection ? $selecionados : collect([$selecionados]);

            foreach ($selecionados as $produto) {
                $quantidadeVendida = random_int(1, min(5, $produto->quantidade));
                $precoUnitario = round($produto->preco, 2);
                $subtotal = round($precoUnitario * $quantidadeVendida, 2);
                $total += $subtotal;

                $itensDaVenda[] = [
                    'product_id' => $produto->id,
                    'quantidade' => $quantidadeVendida,
                    'preco_unitario' => $precoUnitario,
                    'subtotal' => $subtotal,
                ];

                $produto->quantidade -= $quantidadeVendida;
                $produto->save();
            }

            if (empty($itensDaVenda)) {
                continue;
            }

            $total = round($total, 2);
            $dataVenda = Carbon::now()->subDays(random_int(0, 120));

            $sale = Sale::create([
                'client_id' => $clientes->random()->id,
                'data' => $dataVenda->toDateString(),
                'total_geral' => $total,
                'status' => $statusPool[$i] ?? 'pago',
                'user_id' => $user->id,
                'created_at' => $dataVenda,
                'updated_at' => $dataVenda,
            ]);

            $sale->forceFill(['total' => $total])->save();

            foreach ($itensDaVenda as $item) {
                SaleItem::create(array_merge($item, [
                    'sale_id' => $sale->id,
                    'created_at' => $dataVenda,
                    'updated_at' => $dataVenda,
                ]));
            }
        }
    }
}
