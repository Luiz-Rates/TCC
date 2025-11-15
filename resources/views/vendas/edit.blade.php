@extends('layouts.app')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="request('redirect_to') ?: route('vendas.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Vendas</span>
            <h2 class="text-3xl font-bold text-white">Editar Venda</h2>
            <p class="text-sm text-slate-400">Atualize informações da venda, ajuste produtos e mantenha os registros em dia.</p>
        </div>

        <form action="{{ route('vendas.update', $venda->id) }}" method="POST" class="mt-8 space-y-6">
            @csrf
            @method('PUT')
            @if (request('redirect_to'))
                <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="client_id" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Cliente</label>
                    <select name="client_id" id="client_id"
                        class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $venda->client_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="data" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Data</label>
                    <input type="date" name="data" id="data" value="{{ $venda->data ? date('Y-m-d', strtotime($venda->data)) : '' }}"
                        class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                </div>
            </div>

            <div class="space-y-5">
                <div class="flex flex-wrap items-end justify-between gap-2">
                    <h3 class="text-lg font-semibold text-white">Produtos</h3>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Atualize itens e quantidades</span>
                </div>

                <div id="produtos" class="space-y-5">
                    @foreach($venda->items as $item)
                        <div class="produto flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 transition lg:flex-row lg:items-end lg:gap-6"
                            data-produto-original-id="{{ $item->product_id }}"
                            data-quantidade-original="{{ $item->quantidade }}">
                            <div class="flex-1 space-y-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Produto</span>
                                <select name="products[]" class="js-select2-produto w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}"
                                            data-preco="{{ $produto->preco }}"
                                            data-foto="{{ $produto->foto ? asset('storage/' . $produto->foto) : '' }}"
                                            data-quantidade="{{ $produto->quantidade }}"
                                            data-alerta="{{ $produto->quantidade < 1 ? 'Sem estoque disponível' : '' }}"
                                            @disabled($produto->quantidade < 1 && $item->product_id !== $produto->id)
                                            {{ $item->product_id == $produto->id ? 'selected' : '' }}>
                                            {{ $produto->nome }} {{ $produto->quantidade > 0 ? '(' . $produto->quantidade . ' em estoque)' : '(sem estoque)' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="estoque-info text-xs font-medium text-slate-400">Selecione um produto para visualizar o estoque.</p>
                            </div>

                            <div class="w-full space-y-2 lg:w-32">
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Quantidade</span>
                                <input type="number" name="quantidades[]" placeholder="Qtd" value="{{ $item->quantidade }}"
                                    class="w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40" min="1">
                            </div>

                            <button type="button"
                                class="remove-produto hidden shrink-0 rounded-2xl border border-rose-500/50 bg-rose-600/90 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-400/50 lg:self-center">
                                Remover
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addProduto()"
                    class="group inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/50 bg-blue-600/80 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 sm:w-auto">
                    <svg class="h-5 w-5 transition group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Produto
                </button>
            </div>

            <div class="space-y-2">
                <label for="status" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                    <option value="pago" {{ $venda->status == 'pago' ? 'selected' : '' }}>Pago</option>
                    <option value="fiado" {{ $venda->status == 'fiado' ? 'selected' : '' }}>Em aberto</option>
                </select>
            </div>

            <button type="submit"
                class="w-full rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-lg font-semibold uppercase tracking-wide text-white shadow-xl shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60">
                Atualizar Venda
            </button>
        </form>
    </div>

    <template id="template-produto">
        <div class="produto flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 transition lg:flex-row lg:items-end lg:gap-6"
            data-produto-original-id=""
            data-quantidade-original="0">
            <div class="flex-1 space-y-2">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Produto</span>
                <select name="products[]" class="js-select2-produto w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}"
                            data-preco="{{ $produto->preco }}"
                            data-foto="{{ $produto->foto ? asset('storage/' . $produto->foto) : '' }}"
                            data-quantidade="{{ $produto->quantidade }}"
                            data-alerta="{{ $produto->quantidade < 1 ? 'Sem estoque disponível' : '' }}"
                            @disabled($produto->quantidade < 1)
                        >
                            {{ $produto->nome }} {{ $produto->quantidade > 0 ? '(' . $produto->quantidade . ' em estoque)' : '(sem estoque)' }}
                        </option>
                    @endforeach
                </select>
                <p class="estoque-info text-xs font-medium text-slate-400">Selecione um produto para visualizar o estoque.</p>
            </div>

            <div class="w-full space-y-2 lg:w-32">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Quantidade</span>
                <input type="number" name="quantidades[]" placeholder="Qtd" value="1"
                    class="w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40" min="1">
            </div>

            <button type="button"
                class="remove-produto shrink-0 rounded-2xl border border-rose-500/50 bg-rose-600/90 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-400/50 lg:self-center">
                Remover
            </button>
        </div>
    </template>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function escaparAtributo(valor) {
            return (valor || '').toString().replace(/"/g, '&quot;');
        }

        function formatProduto(option) {
            if (!option.id) return option.text;
            const $elemento = $(option.element);
            const preco = $elemento.data('preco');
            const foto = $elemento.data('foto');
            const alerta = $elemento.data('alerta');
            const nomeProduto = option.text || '';
            const nomeAttr = escaparAtributo(nomeProduto);
            const precoFormatado = preco ? new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(parseFloat(preco)) : null;
            const fotoTemplate = foto
                ? `<img src="${foto}" alt="${nomeAttr}" class="h-12 w-12 rounded-2xl border border-slate-800/60 object-cover shadow-sm shadow-slate-950/60">`
                : `<div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-dashed border-slate-700 bg-slate-900/70 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Sem foto</div>`;

            const badge = alerta
                ? `<span class="mt-1 inline-flex items-center gap-1 rounded-full border border-amber-500/50 bg-amber-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-200">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 3h.01M4.293 17.293a1 1 0 010-1.414l7.071-7.071a1 1 0 011.414 0l7.071 7.071a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0l-7.071-7.071z" />
                        </svg>
                        ${alerta}
                   </span>`
                : '';

            return $(`<div class="flex items-center gap-3">
                        ${fotoTemplate}
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-100">${nomeProduto}</span>
                            ${precoFormatado ? `<span class="text-xs font-medium text-slate-400">${precoFormatado}</span>` : ''}
                            ${badge}
                        </div>
                      </div>`);
        }

        function formatProdutoSelecionado(option) {
            if (!option.id) return option.text;
            const $elemento = $(option.element);
            if (!$elemento || !$elemento.length) return option.text;
            const foto = $elemento.data('foto');
            const alerta = $elemento.data('alerta');
            const nomeProduto = option.text || '';
            const nomeAttr = escaparAtributo(nomeProduto);
            if (!foto) return option.text;

            const badge = alerta
                ? `<span class="ms-2 rounded-full border border-amber-500/50 bg-amber-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-200">${alerta}</span>`
                : '';

            return $(`<span class="flex items-center gap-2">
                        <img src="${foto}" alt="${nomeAttr}" class="h-6 w-6 rounded-lg border border-slate-800/60 object-cover">
                        <span>${nomeProduto}</span>
                        ${badge}
                      </span>`);
        }

        function pluralizarUnidades(qtd) {
            return qtd === 1 ? 'unidade' : 'unidades';
        }

        function atualizarMensagemEstoque($row, mensagem, critico = false) {
            const $info = $row.find('.estoque-info');
            if (!$info.length) return;
            $info.text(mensagem);
            $info.toggleClass('text-rose-400', critico);
            $info.toggleClass('text-slate-400', !critico);
        }

        function produtoJaSelecionado(produtoId, $origem) {
            if (!produtoId) {
                return false;
            }

            let duplicado = false;

            $('.js-select2-produto').not($origem).each(function () {
                if ($(this).val() && String($(this).val()) === String(produtoId)) {
                    duplicado = true;
                    return false;
                }
            });

            return duplicado;
        }

        function estoqueComReserva($row, estoqueBase, produtoId) {
            const originalProdutoId = Number($row.data('produtoOriginalId')) || null;
            const originalQuantidade = Number($row.data('quantidadeOriginal')) || 0;

            if (produtoId && originalProdutoId && originalProdutoId === produtoId) {
                return estoqueBase + originalQuantidade;
            }

            return estoqueBase;
        }

        function definirEstoqueDisponivel($row, estoqueBase, produtoId) {
            const estoqueNormalizado = Number(estoqueBase) || 0;
            const estoqueTotal = estoqueComReserva($row, estoqueNormalizado, produtoId);

            $row.data('estoqueBase', estoqueNormalizado);
            $row.data('estoqueDisponivel', estoqueTotal);

            const $quantidadeInput = $row.find('input[name="quantidades[]"]');

            if (estoqueTotal > 0) {
                const mensagem = `Em estoque: ${estoqueTotal} ${pluralizarUnidades(estoqueTotal)}.`;
                atualizarMensagemEstoque($row, mensagem, false);
                $quantidadeInput.prop('disabled', false).attr('max', estoqueTotal);

                const valorAtual = Number($quantidadeInput.val());
                if (valorAtual > estoqueTotal) {
                    $quantidadeInput.val(estoqueTotal);
                }
            } else {
                atualizarMensagemEstoque($row, 'Sem unidades disponíveis. Reponha o estoque para continuar.', true);
                $quantidadeInput.val('').prop('disabled', true).attr('max', 0);
            }
        }

        function resetEstoque($row) {
            $row.removeData('estoqueBase');
            $row.removeData('estoqueDisponivel');
            atualizarMensagemEstoque($row, 'Selecione um produto para visualizar o estoque.', false);

            const $quantidadeInput = $row.find('input[name="quantidades[]"]');
            $quantidadeInput.prop('disabled', false).attr('max', '');

            if (!$quantidadeInput.val()) {
                $quantidadeInput.val(1);
            }
        }

        function initSelect($elements) {
            $elements.select2({
                placeholder: 'Digite para pesquisar...',
                allowClear: true,
                width: '100%',
                dropdownParent: $(document.body),
                templateResult: formatProduto,
                templateSelection: formatProdutoSelecionado,
                escapeMarkup: (markup) => markup
            }).each(function () {
                const $container = $(this).next('.select2-container');
                if ($container.length && !$container.hasClass('select2-tailwind')) {
                    $container.addClass('select2-tailwind');
                }
            }).each(function () {
                const $select = $(this);
                const $row = $select.closest('.produto');
                const $selected = $select.find('option:selected');

                if ($selected.length && $selected.val()) {
                    const estoqueBase = Number($selected.data('quantidade')) || 0;
                    const produtoId = Number($select.val());
                    definirEstoqueDisponivel($row, estoqueBase, produtoId);
                } else {
                    resetEstoque($row);
                }
            });

            $elements.on('select2:select', function (e) {
                const $select = $(this);
                const $row = $select.closest('.produto');
                const $option = $(e.params.data.element);
                const estoqueBase = Number($option.data('quantidade')) || 0;
                const produtoId = Number($select.val());

                if (produtoJaSelecionado(produtoId, $select)) {
                    $select.val(null).trigger('change');
                    resetEstoque($row);
                    window.alert('Este produto já foi adicionado. Ajuste a quantidade no item existente.');
                    return;
                }

                definirEstoqueDisponivel($row, estoqueBase, produtoId);
            });

            $elements.on('select2:clear', function () {
                const $select = $(this);
                const $row = $select.closest('.produto');
                resetEstoque($row);
            });
        }

        function atualizarBotoesRemocao() {
            const rows = document.querySelectorAll('#produtos .produto');
            rows.forEach((row) => {
                const btn = row.querySelector('.remove-produto');
                if (!btn) return;
                btn.classList.toggle('hidden', rows.length === 1);
            });
        }

        function addProduto() {
            const template = document.getElementById('template-produto');
            const produtos = document.getElementById('produtos');
            const novo = template.content.firstElementChild.cloneNode(true);
            produtos.appendChild(novo);
            const $novo = $(novo);
            initSelect($novo.find('.js-select2-produto'));
            resetEstoque($novo);
            atualizarBotoesRemocao();
        }

        $(function () {
            initSelect($('.js-select2-produto'));
            atualizarBotoesRemocao();

            $('#produtos').on('input', 'input[name="quantidades[]"]', function () {
                const $input = $(this);

                if ($input.prop('disabled')) {
                    return;
                }

                const $row = $input.closest('.produto');
                let valor = Number($input.val()) || 0;

                if (valor < 1) {
                    valor = 1;
                    $input.val(1);
                }

                const estoqueDisponivel = Number($row.data('estoqueDisponivel'));
                if (Number.isFinite(estoqueDisponivel) && estoqueDisponivel > 0 && valor > estoqueDisponivel) {
                    valor = estoqueDisponivel;
                    $input.val(estoqueDisponivel);
                }

                const estoqueBase = $row.data('estoqueBase');
                if (typeof estoqueBase !== 'undefined') {
                    const produtoId = Number($row.find('.js-select2-produto').val()) || null;
                    definirEstoqueDisponivel($row, estoqueBase, produtoId);
                }
            });

            $('#produtos').on('click', '.remove-produto', function () {
                const rows = $('#produtos .produto');
                if (rows.length > 1) {
                    $(this).closest('.produto').remove();
                    atualizarBotoesRemocao();
                }
            });
        });
    </script>
@endsection
