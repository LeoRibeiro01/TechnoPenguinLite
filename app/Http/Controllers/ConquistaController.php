<?php

namespace App\Http\Controllers;

use App\Models\Conquista;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DE CONQUISTAS
 *   RF17 -> o usuário vê suas conquistas e medalhas (as ganhas e as que ainda faltam)
 */
class ConquistaController extends Controller
{
    // GET /conquistas
    public function index()
    {
        return view('conquistas.index', [
            'conquistas' => Conquista::orderBy('tipo')->orderBy('meta')->get(),
            // keyBy('id'): deixa a lista indexada pelo id -> $minhas[3] é a conquista 3, se o usuário tiver
            'minhas'     => Auth::user()->conquistas->keyBy('id'),
        ]);
    }
}
