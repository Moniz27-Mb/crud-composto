<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function utilizadores()
    {
        $utilizadores = User::all();
        return view('admin.utilizadores', compact('utilizadores'));
    }

    public function criarUtilizador(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.utilizadores')->with('sucesso', 'Utilizador criado com sucesso!');
    }

    public function alterarRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,user']);
        $user->update(['role' => $request->role]);
        return redirect()->route('admin.utilizadores')->with('sucesso', 'Role atualizado com sucesso!');
    }

    public function eliminarUtilizador(User $user)
    {
        $user->delete();
        return redirect()->route('admin.utilizadores')->with('sucesso', 'Utilizador eliminado com sucesso!');
    }
}
