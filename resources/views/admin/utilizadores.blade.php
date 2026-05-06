@extends('layouts.app')

@section('content')
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-6">
            <h2 class="font-semibold text-xl text-gray-800">Gestão de Utilizadores</h2>
        </div>
    </header>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('sucesso'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('sucesso') }}
            </div>
        @endif

        {{-- Formulário criar utilizador --}}
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Novo Utilizador</h3>
            <form method="POST" action="{{ route('admin.criarUtilizador') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nome</label>
                        <input type="text" name="name" required
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" />
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" />
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" />
                        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Role</label>
                        <select name="role" required
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">
                            <option value="user">Utilizador</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Criar Utilizador
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabela de utilizadores --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Utilizadores Registados</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Nome</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($utilizadores as $utilizador)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $utilizador->id }}</td>
                        <td class="px-4 py-2">{{ $utilizador->name }}</td>
                        <td class="px-4 py-2">{{ $utilizador->email }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('admin.alterarRole', $utilizador) }}">
                                @csrf
                                @method('PUT')
                                <select name="role" onchange="this.form.submit()"
                                    class="border rounded px-2 py-1 text-sm">
                                    <option value="user" {{ $utilizador->role === 'user' ? 'selected' : '' }}>Utilizador</option>
                                    <option value="admin" {{ $utilizador->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-2">
                            @if($utilizador->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.eliminarUtilizador', $utilizador) }}"
                                onsubmit="return confirm('Tens a certeza?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Eliminar
                                </button>
                            </form>
                            @else
                                <span class="text-gray-400 text-sm">Tu</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
