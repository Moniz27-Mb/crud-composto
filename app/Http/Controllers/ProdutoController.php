<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Auth::user()->produtos;
        return view('produtos.index', compact('produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'       => 'required|string|max:255',
            'descricao'  => 'required|string',
            'preco'      => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:1',
            'imagem'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dados = $request->only(['nome', 'descricao', 'preco', 'quantidade']);
        $dados['user_id'] = Auth::id();

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('imagens', 'public');
        }

        Produto::create($dados);

        return redirect()->route('produtos.index')->with('sucesso', 'Produto criado com sucesso!');
    }

    public function update(Request $request, Produto $produto)
    {
        abort_if($produto->user_id !== Auth::id(), 403);

        $request->validate([
            'nome'       => 'required|string|max:255',
            'descricao'  => 'required|string',
            'preco'      => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:1',
            'imagem'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dados = $request->only(['nome', 'descricao', 'preco', 'quantidade']);

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $dados['imagem'] = $request->file('imagem')->store('imagens', 'public');
        }

        $produto->update($dados);

        return redirect()->route('produtos.index')->with('sucesso', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        abort_if($produto->user_id !== Auth::id(), 403);
        $produto->delete();
        return redirect()->route('produtos.index')->with('sucesso', 'Produto movido para a lixeira!');
    }

    public function lixeira()
    {
        $produtos = Produto::onlyTrashed()->where('user_id', Auth::id())->get();
        return view('produtos.lixeira', compact('produtos'));
    }

    public function restaurar($id)
    {
        $produto = Produto::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $produto->restore();
        return redirect()->route('produtos.lixeira')->with('sucesso', 'Produto restaurado com sucesso!');
    }

    public function forceApagar($id)
    {
        $produto = Produto::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }
        $produto->forceDelete();
        return redirect()->route('produtos.lixeira')->with('sucesso', 'Produto apagado definitivamente!');
    }
}
