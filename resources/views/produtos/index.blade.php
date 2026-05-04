@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 40px auto; padding: 0 15px; font-family: 'Segoe UI', sans-serif;">

    @if(session('sucesso'))
        <div style="background:#c6f6d5; color:#276749; padding:12px 20px; border-radius:8px; margin-bottom:20px;">
            <i class="fas fa-check-circle"></i> {{ session('sucesso') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 10px;">
        <h1 style="color: #2d3748; font-size: 24px; margin: 0;">
            <i class="fas fa-box" style="color:#4f46e5;"></i> Lista de Produtos
        </h1>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('produtos.lixeira') }}"
               style="background: #718096; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px;">
                <i class="fas fa-trash-can"></i> Lixeira ({{ \App\Models\Produto::onlyTrashed()->count() }})
            </a>
            <button onclick="abrirModal('modalCriar')"
               style="background: #4f46e5; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: bold; font-size: 14px; cursor: pointer;">
                <i class="fas fa-plus"></i> Criar Produto
            </button>
        </div>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
            <thead>
                <tr style="background: #4f46e5; color: white;">
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Imagem</th>
                    <th style="padding: 15px; text-align: left;">Nome</th>
                    <th style="padding: 15px; text-align: left;">Descrição</th>
                    <th style="padding: 15px; text-align: left;">Preço</th>
                    <th style="padding: 15px; text-align: left;">Qtd</th>
                    <th style="padding: 15px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produtos as $produto)
                <tr style="border-bottom: 1px solid #e2e8f0;"
                    onmouseover="this.style.background='#f7f7ff'"
                    onmouseout="this.style.background='white'">
                    <td style="padding: 14px 15px; color: #718096;">{{ $produto->id }}</td>
                    <td style="padding: 14px 15px;">
                        @if($produto->imagem)
                            <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Imagem"
                                 style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                        @else
                            <span style="color:#a0aec0; font-size:12px;"><i class="fas fa-image"></i></span>
                        @endif
                    </td>
                    <td style="padding: 14px 15px; font-weight: 600; color: #2d3748;">{{ $produto->nome }}</td>
                    <td style="padding: 14px 15px; color: #718096; max-width: 200px; word-wrap: break-word;">{{ $produto->descricao }}</td>
                    <td style="padding: 14px 15px; color: #38a169; font-weight: bold;">{{ number_format($produto->preco, 2) }} Kz</td>
                    <td style="padding: 14px 15px; color: #2d3748;">{{ $produto->quantidade }}</td>
                    <td style="padding: 14px 15px; text-align: center; white-space: nowrap;">
                        <button onclick="abrirVer('{{ addslashes($produto->nome) }}', '{{ addslashes($produto->descricao) }}', '{{ $produto->preco }}', '{{ $produto->quantidade }}', '{{ $produto->imagem ? asset('storage/' . $produto->imagem) : '' }}')"
                                style="background: #3182ce; color: white; padding: 6px 12px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; margin: 2px;">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="abrirEditar({{ $produto->id }}, '{{ addslashes($produto->nome) }}', '{{ addslashes($produto->descricao) }}', '{{ $produto->preco }}', '{{ $produto->quantidade }}')"
                                style="background: #d69e2e; color: white; padding: 6px 12px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; margin: 2px;">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Mover para a lixeira?')"
                                    style="background: #e53e3e; color: white; padding: 6px 12px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; margin: 2px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($produtos->isEmpty())
        <div style="text-align: center; padding: 60px; color: #a0aec0;">
            <i class="fas fa-box-open" style="font-size:40px; margin-bottom:10px; display:block;"></i>
            <p style="font-size: 18px;">Nenhum produto encontrado.</p>
        </div>
        @endif
    </div>
</div>

<!-- MODAL CRIAR -->
<div id="modalCriar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div style="background:white; border-radius:12px; padding:40px; width:90%; max-width:500px; max-height:90vh; overflow-y:auto;">
        <h2 style="color:#2d3748; margin-bottom:25px;"><i class="fas fa-plus-circle" style="color:#4f46e5;"></i> Criar Produto</h2>
        <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Nome</label>
                <input type="text" name="nome" required
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Descrição</label>
                <textarea name="descricao" rows="3" required
                          style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;"></textarea>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Preço (Kz)</label>
                <input type="number" name="preco" step="0.01" min="0" required
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Quantidade</label>
                <input type="number" name="quantidade" min="1" required
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom:25px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">
                    <i class="fas fa-image"></i> Imagem
                </label>
                <input type="file" name="imagem" accept="image/*"
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="display:flex; justify-content:space-between;">
                <button type="button" onclick="fecharModal('modalCriar')"
                        style="background:#718096; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer;">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit"
                        style="background:#4f46e5; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:bold;">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VER -->
<div id="modalVer" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div style="background:white; border-radius:12px; padding:40px; width:90%; max-width:500px;">
        <h2 style="color:#2d3748; margin-bottom:25px;"><i class="fas fa-eye" style="color:#3182ce;"></i> Detalhes do Produto</h2>
        <div id="verImagem" style="margin-bottom:15px; text-align:center;"></div>
        <div style="margin-bottom:15px; padding:12px; background:#f7fafc; border-radius:8px;">
            <label style="color:#718096; font-size:12px;">NOME</label>
            <p id="verNome" style="color:#2d3748; font-weight:600; margin:5px 0 0;"></p>
        </div>
        <div style="margin-bottom:15px; padding:12px; background:#f7fafc; border-radius:8px;">
            <label style="color:#718096; font-size:12px;">DESCRIÇÃO</label>
            <p id="verDescricao" style="color:#2d3748; margin:5px 0 0;"></p>
        </div>
        <div style="margin-bottom:15px; padding:12px; background:#f7fafc; border-radius:8px;">
            <label style="color:#718096; font-size:12px;">PREÇO</label>
            <p id="verPreco" style="color:#38a169; font-weight:bold; margin:5px 0 0;"></p>
        </div>
        <div style="margin-bottom:25px; padding:12px; background:#f7fafc; border-radius:8px;">
            <label style="color:#718096; font-size:12px;">QUANTIDADE</label>
            <p id="verQuantidade" style="color:#2d3748; font-weight:600; margin:5px 0 0;"></p>
        </div>
        <button onclick="fecharModal('modalVer')"
                style="background:#718096; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer;">
            <i class="fas fa-times"></i> Fechar
        </button>
    </div>
</div>

<!-- MODAL EDITAR -->
<div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div style="background:white; border-radius:12px; padding:40px; width:90%; max-width:500px; max-height:90vh; overflow-y:auto;">
        <h2 style="color:#2d3748; margin-bottom:25px;"><i class="fas fa-pen" style="color:#d69e2e;"></i> Editar Produto</h2>
        <form id="formEditar" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Nome</label>
                <input type="text" id="editNome" name="nome" required
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Descrição</label>
                <textarea id="editDescricao" name="descricao" rows="3" required
                          style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;"></textarea>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Preço (Kz)</label>
                <input type="number" id="editPreco" name="preco" step="0.01" min="0" required
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">Quantidade</label>
                <input type="number" id="editQuantidade" name="quantidade" min="1" required
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom:25px;">
                <label style="display:block; color:#4a5568; font-weight:600; margin-bottom:6px;">
                    <i class="fas fa-image"></i> Nova Imagem (opcional)
                </label>
                <input type="file" name="imagem" accept="image/*"
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="display:flex; justify-content:space-between;">
                <button type="button" onclick="fecharModal('modalEditar')"
                        style="background:#718096; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer;">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit"
                        style="background:#d69e2e; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:bold;">
                    <i class="fas fa-save"></i> Atualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
}
function abrirVer(nome, descricao, preco, quantidade, imagem) {
    document.getElementById('verNome').textContent = nome;
    document.getElementById('verDescricao').textContent = descricao;
    document.getElementById('verPreco').textContent = parseFloat(preco).toFixed(2) + ' Kz';
    document.getElementById('verQuantidade').textContent = quantidade + ' unidades';
    const imgDiv = document.getElementById('verImagem');
    if (imagem) {
        imgDiv.innerHTML = '<img src="' + imagem + '" style="width:100px; height:100px; object-fit:cover; border-radius:10px;">';
    } else {
        imgDiv.innerHTML = '<i class="fas fa-image" style="font-size:40px; color:#a0aec0;"></i>';
    }
    abrirModal('modalVer');
}
function abrirEditar(id, nome, descricao, preco, quantidade) {
    document.getElementById('formEditar').action = '/produtos/' + id;
    document.getElementById('editNome').value = nome;
    document.getElementById('editDescricao').value = descricao;
    document.getElementById('editPreco').value = preco;
    document.getElementById('editQuantidade').value = quantidade;
    abrirModal('modalEditar');
}
window.onclick = function(e) {
    ['modalCriar', 'modalVer', 'modalEditar'].forEach(function(id) {
        var modal = document.getElementById(id);
        if (e.target === modal) fecharModal(id);
    });
}
</script>
@endsection
