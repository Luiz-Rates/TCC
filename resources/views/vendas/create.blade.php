@extends('layouts.app')

@section('head')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('vendas.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Vendas</span>
            <h2 class="text-3xl font-bold text-white">Nova Venda</h2>
            <p class="text-sm text-slate-400">Selecione o cliente, escolha os produtos e defina os detalhes da venda.</p>
        </div>

        <form action="{{ route('vendas.store') }}" method="POST" class="mt-8 space-y-8">
            @csrf

            {{-- Cliente --}}
            <div class="space-y-2">
                <label for="client_id" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Cliente</label>
                <select name="client_id" id="client_id" class="w-full">
                    <option value="">Digite para pesquisar cliente...</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" @selected(old('client_id') == $cliente->id)>{{ $cliente->nome }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Data --}}
            <div class="space-y-2">
                <label for="data" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Data</label>
                <input type="date" name="data" value="{{ old('data', date('Y-m-d')) }}"
                    class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>

            {{-- Produtos --}}
            <div class="space-y-5">
                <div class="flex flex-wrap items-end justify-between gap-2">
                    <h3 class="text-lg font-semibold text-white">Produtos</h3>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Adicione itens à venda</span>
                </div>

                <div id="produtos" class="space-y-5">
                    <div
                        class="produto-row flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/40 transition hover:border-blue-500/50 focus-within:border-blue-500/60 lg:flex-row lg:items-end lg:gap-6">
                        <div class="flex-1 space-y-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Produto</span>
                            <select name="products[]" class="js-select2-produto w-full">
                                <option value="">Digite para pesquisar produto...</option>
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

                        <div class="w-full space-y-2 lg:w-40">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Preço unitário</span>
                            <input type="text" name="precos[]" readonly
                                class="preco-unitario block w-full rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-2.5 text-right text-slate-200 placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                                placeholder="R$ 0,00">
                        </div>

                        <div class="w-full space-y-2 lg:w-32">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Quantidade</span>
                            <input type="number" name="quantidades[]" value="1" min="1"
                                class="qtd block w-full rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-2.5 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                        </div>

                        <div class="w-full space-y-2 lg:w-40">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</span>
                            <input type="text" name="totais[]" readonly
                                class="total-linha block w-full rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-2.5 text-right text-slate-200 placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                                placeholder="R$ 0,00">
                        </div>

                        <button type="button"
                            class="remove-produto hidden shrink-0 rounded-2xl border border-rose-500/50 bg-rose-600/90 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-400/50 lg:self-center">
                            Remover
                        </button>
                    </div>
                </div>

                {{-- Template para novos produtos --}}
                <template id="tpl-produto-row">
                    <div
                        class="produto-row flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/40 transition hover:border-blue-500/50 focus-within:border-blue-500/60 lg:flex-row lg:items-end lg:gap-6">
                        <div class="flex-1 space-y-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Produto</span>
                            <select name="products[]" class="js-select2-produto w-full">
                                <option value="">Digite para pesquisar produto...</option>
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

                        <div class="w-full space-y-2 lg:w-40">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Preço unitário</span>
                            <input type="text" name="precos[]" readonly
                                class="preco-unitario block w-full rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-2.5 text-right text-slate-200 placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                                placeholder="R$ 0,00">
                        </div>

                        <div class="w-full space-y-2 lg:w-32">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Quantidade</span>
                            <input type="number" name="quantidades[]" value="1" min="1"
                                class="qtd block w-full rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-2.5 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                        </div>

                        <div class="w-full space-y-2 lg:w-40">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</span>
                            <input type="text" name="totais[]" readonly
                                class="total-linha block w-full rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-2.5 text-right text-slate-200 placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                                placeholder="R$ 0,00">
                        </div>

                        <button type="button"
                            class="remove-produto shrink-0 rounded-2xl border border-rose-500/50 bg-rose-600/90 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-400/50 lg:self-center">
                            Remover
                        </button>
                    </div>
                </template>

                {{-- Adicionar Produto --}}
                <button type="button" id="addProduto"
                    class="group inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/50 bg-blue-600/80 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 sm:w-auto">
                    <svg class="h-5 w-5 transition group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Produto
                </button>
            </div>

            {{-- Status --}}
            <div class="space-y-2">
                <label for="status" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                    <option value="pago" {{ old('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                    <option value="fiado" {{ old('status') == 'fiado' ? 'selected' : '' }}>Em aberto</option>
                </select>
            </div>

            {{-- Total Geral --}}
            <div
                class="flex flex-col items-center justify-between gap-3 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-6 py-4 text-emerald-200 shadow-inner shadow-emerald-900/40 sm:flex-row sm:gap-0">
                <span class="text-lg font-semibold tracking-wide text-emerald-200">Total Geral</span>
                <span id="total-geral" class="text-2xl font-bold text-emerald-300">R$ 0,00</span>
            </div>
            <input type="hidden" name="total_geral" id="total_geral_input" value="0">


            {{-- Salvar --}}
            <button type="submit"
                class="group relative flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/50 bg-blue-600/90 px-6 py-3 text-lg font-semibold uppercase tracking-wide text-white shadow-xl shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60">
                <svg class="h-5 w-5 transition group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Salvar Venda
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            const formatadorMoeda = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

            function escaparAtributo(valor) {
                return (valor || '').toString().replace(/"/g, '&quot;');
            }

            function normalize(str) {
                return (str || "").toString().normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase();
            }

            function valorNumerico(valor) {
                if (typeof valor === 'number') {
                    return Number.isFinite(valor) ? valor : 0;
                }

                return parseFloat(String(valor).replace(',', '.')) || 0;
            }

            function formatarParaCampo(valor) {
                return valor > 0 ? valor.toFixed(2) : '';
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

            function definirEstoqueDisponivel($row, estoque) {
                const estoqueNormalizado = Number(estoque) || 0;

                $row.data('estoqueBase', estoqueNormalizado);
                $row.data('estoqueDisponivel', estoqueNormalizado);

                const $quantidadeInput = $row.find('.qtd');

                if (estoqueNormalizado > 0) {
                    const mensagem = `Em estoque: ${estoqueNormalizado} ${pluralizarUnidades(estoqueNormalizado)}.`;
                    atualizarMensagemEstoque($row, mensagem, false);
                    $quantidadeInput.prop('disabled', false).attr('max', estoqueNormalizado);

                    const valorAtual = valorNumerico($quantidadeInput.val());
                    if (valorAtual > estoqueNormalizado) {
                        $quantidadeInput.val(estoqueNormalizado);
                        calcularTotalLinha($row);
                    }
                } else {
                    atualizarMensagemEstoque($row, 'Sem unidades disponíveis. Reponha o estoque para continuar.', true);
                    $quantidadeInput.val('').prop('disabled', true).attr('max', 0);
                    $row.find('.preco-unitario').val('');
                    $row.find('.total-linha').val('');
                    atualizarTotalGeral();
                }
            }

            function resetEstoque($row) {
                $row.removeData('estoqueBase');
                $row.removeData('estoqueDisponivel');
                atualizarMensagemEstoque($row, 'Selecione um produto para visualizar o estoque.', false);
                const $quantidadeInput = $row.find('.qtd');
                $quantidadeInput.prop('disabled', false).attr('max', '').val(1);
                calcularTotalLinha($row);
            }

            function atualizarTotalGeral() {
                let total = 0;
                $('.total-linha').each(function () {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total-geral').text(formatadorMoeda.format(total));
                $('#total_geral_input').val(total.toFixed(2));
            }


            function aplicarTemaSelect2($elements) {
                $elements.each(function () {
                    const $select = $(this);
                    const $container = $select.next('.select2-container');
                    if ($container.length && !$container.hasClass('select2-tailwind')) {
                        $container.addClass('select2-tailwind');
                    }
                });
            }

            function customMatcher(params, data) {
                if ($.trim(params.term) === '') return data;
                if (!data.text) return null;
                return normalize(data.text).includes(normalize(params.term)) ? data : null;
            }

            function formatProduto(option) {
                if (!option.id) return option.text;
                const $elemento = $(option.element);
                const preco = $elemento.data('preco');
                const foto = $elemento.data('foto');
                const alerta = $elemento.data('alerta');
                const nomeProduto = option.text || '';
                const nomeAttr = escaparAtributo(nomeProduto);
                const precoFormatado = preco ? formatadorMoeda.format(valorNumerico(preco)) : null;
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

            function initSelect($el, withSearch = true, isProduto = false) {
                $el.select2({
                    placeholder: 'Digite para pesquisar...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $(document.body),
                    minimumResultsForSearch: withSearch ? 0 : Infinity,
                    matcher: withSearch ? customMatcher : null,
                    templateResult: isProduto ? formatProduto : undefined,
                    templateSelection: isProduto ? formatProdutoSelecionado : undefined,
                    escapeMarkup: (markup) => markup
                });

                aplicarTemaSelect2($el);

                // Preencher preço unitário e calcular total ao selecionar produto
                if (isProduto) {
                    $el.each(function () {
                        const $select = $(this);
                        const $row = $select.closest('.produto-row');
                        const $selected = $select.find('option:selected');

                        if ($selected.length && $selected.val()) {
                            const precoInicial = valorNumerico($selected.data('preco'));
                            if (precoInicial) {
                                $row.find('.preco-unitario').val(formatarParaCampo(precoInicial));
                            }
                            const estoqueInicial = Number($selected.data('quantidade')) || 0;
                            definirEstoqueDisponivel($row, estoqueInicial);
                            calcularTotalLinha($row);
                        } else {
                            resetEstoque($row);
                        }
                    });

                    $el.on('select2:select', function (e) {
                        const $select = $(this);
                        const $option = $(e.params.data.element);
                        const produtoId = $option.val();
                        const $row = $select.closest('.produto-row');

                        if (produtoJaSelecionado(produtoId, $select)) {
                            $select.val(null).trigger('change');
                            resetEstoque($row);
                            window.alert('Este produto já foi adicionado. Ajuste a quantidade no item existente.');
                            return;
                        }

                        const preco = $option.data('preco');
                        const estoque = Number($option.data('quantidade')) || 0;

                        $row.find('.preco-unitario').val(formatarParaCampo(valorNumerico(preco)));
                        definirEstoqueDisponivel($row, estoque);
                        calcularTotalLinha($row);
                    });

                    $el.on('select2:clear', function () {
                        const $row = $(this).closest('.produto-row');
                        $row.find('.preco-unitario').val('');
                        $row.find('.total-linha').val('');
                        resetEstoque($row);
                    });
                }
            }

            function calcularTotalLinha($row) {
                const preco = valorNumerico($row.find('.preco-unitario').val());
                const qtd = valorNumerico($row.find('.qtd').val());
                const total = preco * qtd;
                $row.find('.total-linha').val(formatarParaCampo(total));
                atualizarTotalGeral();
            }

            // Cliente
            initSelect($('#client_id'), true, false);

            // Produtos
            initSelect($('.js-select2-produto'), true, true);

            // Recalcular total ao mudar quantidade
            $('#produtos').on('input', '.qtd', function () {
                const $input = $(this);

                if ($input.prop('disabled')) {
                    return;
                }

                const $row = $input.closest('.produto-row');
                const estoqueDisponivel = Number($row.data('estoqueDisponivel'));
                const estoqueBase = $row.data('estoqueBase');
                let valor = valorNumerico($input.val());

                if (valor < 1) {
                    valor = 1;
                    $input.val(1);
                }

                if (Number.isFinite(estoqueDisponivel) && estoqueDisponivel > 0 && valor > estoqueDisponivel) {
                    valor = estoqueDisponivel;
                    $input.val(estoqueDisponivel);
                }

                calcularTotalLinha($row);

                if (typeof estoqueBase !== 'undefined') {
                    definirEstoqueDisponivel($row, estoqueBase);
                }
            });

            // Adicionar produto
            function atualizarBotoesRemocao() {
                const $rows = $('.produto-row');
                const esconder = $rows.length === 1;
                $rows.find('.remove-produto').toggleClass('hidden', esconder);
            }

            $('#addProduto').on('click', function () {
                const $row = $($('#tpl-produto-row').html());
                $('#produtos').append($row);
                initSelect($row.find('.js-select2-produto'), true, true);
                resetEstoque($row);
                atualizarBotoesRemocao();
            });

            atualizarBotoesRemocao();

            // Remover produto
            $('#produtos').on('click', '.remove-produto', function () {
                if ($('.produto-row').length > 1) {
                    $(this).closest('.produto-row').remove();
                    atualizarTotalGeral();
                    atualizarBotoesRemocao();
                }
            });
        });
    </script>
@endsection
