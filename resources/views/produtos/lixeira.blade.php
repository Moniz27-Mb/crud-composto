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
            <i class="fas fa-trash-can" style="color:#718096;"></i> Lixeira
        </h1>
        <a href="{{ route('produtos.index') }}"
           style="background: #4f46e5; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-arrow-left"></i> Voltar aos Produtos
        </a>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
            <thead>
                <tr style="background: #718096; color: white;">
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Imagem</th>
                    <th style="padding: 15px; text-align: left;">Nome</th>
                    <th style="padding: 15px; text-align: left;">Preco</th>
                    <th style="padding: 15px; text-align: left;">Apagado em</th>
                    <th style="padding: 15px; text-align: center;">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produtos as $produto)
                <tr style="border-bottom: 1px solid #e2e8f0;"
                    onmouseover="this.style.background='#fff5f5'"
                    onmouseout="this.style.background='white'">
                    <td style="padding: 14px 15px; color: #718096;">{{ $produto->id }}</td>
                    <td style="padding: 14px 15px;">
                        @if($produto->imagem)
                            <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Imagem"
                                 style="width:50px; height:50px; object-fit:cover; border-radius:8px; opacity:0.6;">
                        @else
                            <i class="fas fa-image" style="color:#a0aec0; font-size:20px;"></i>
                        @endif
                    </td>
                    <td style="padding: 14px 15px; font-weight: 600; color: #718096;">{{ $produto->nome }}</td>
                    <td style="padding: 14px 15px; color: #718096;">{{ number_format($produto->preco, 2) }} Kz</td>
                    <td style="padding: 14px 15px; color: #e53e3e; font-size: 13px;">{{ $produto->deleted_at->format('d/m/Y H:i') }}</td>
                    <td style="padding: 14px 15px; text-align: center; white-space: nowrap;">
                        <form action="{{ route('produtos.restaurar', $produto->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit"
                                    style="background: #38a169; color: white; padding: 6px 12px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; margin: 2px;">
                                <i class="fas fa-rotate-left"></i> Restaurar
                            </button>
                        </form>
                        <form action="{{ route('produtos.forceApagar', $produto->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apagar definitivamente? Esta acao nao pode ser desfeita!')"
                                    style="background: #e53e3e; color: white; padding: 6px 12px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; margin: 2px;">
                                <i class="fas fa-trash"></i> Apagar definitivamente
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($produtos->isEmpty())
        <div style="text-align: center; padding: 60px; color: #a0aec0;">
            <i class="fas fa-trash-can" style="font-size:40px; margin-bottom:10px; display:block;"></i>
            <p style="font-size: 18px;">A lixeira esta vazia.</p>
        </div>
        @endif
    </div>
</div>
@endsection
