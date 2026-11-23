<?php

namespace App\Http\Controllers;

use App\Support\Pinguim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DO VESTUÁRIO - roupas do pinguim (RF08/RF09)
 *   index   GET  /vestuario          mostra o pinguim e as peças de cada camada
 *   vestir  POST /vestuario/vestir   veste uma peça (ou tira, se vier vazia)
 *   comprar POST /vestuario/comprar  compra uma peça exclusiva com pontos e já veste
 *
 * O catálogo das peças está em app/Support/Pinguim.php.
 * A peça vestida em cada camada fica nas colunas users.roupa, users.pescoco, users.oculos e users.chapeu.
 */
class VestuarioController extends Controller
{
    public function index()
    {
        return view('vestuario.index', [
            'user'    => Auth::user(),
            'camadas' => Pinguim::CAMADAS,
            'saldo'   => Auth::user()->saldoPontos(),
        ]);
    }

    public function vestir(Request $request)
    {
        $request->validate([
            'camada' => 'required|in:roupa,pescoco,oculos,chapeu',
            'item'   => 'nullable',
        ]);
        $user = Auth::user();
        $camada = $request->camada;
        $codigo = $request->item;   // null = tirar a peça desta camada

        if ($codigo !== null) {
            $item = Pinguim::item($codigo);
            // A peça precisa existir, ser desta camada e o usuário precisa tê-la (grátis ou comprada).
            if ($item === null || $item['camada'] !== $camada || !$user->temItem($codigo)) {
                return back()->with('aviso', 'Você ainda não tem esta peça.');
            }
        }

        // $user->$camada: a coluna tem o mesmo nome da camada (ex.: $user->chapeu = 'cartola').
        $user->$camada = $codigo;
        $user->save();

        return redirect()->route('vestuario')->with('aviso', $codigo ? 'Visual atualizado!' : 'Peça removida.');
    }

    public function comprar(Request $request)
    {
        $request->validate(['item' => 'required']);
        $user = Auth::user();
        $item = Pinguim::item($request->item);

        if ($item === null) {
            return back()->with('aviso', 'Peça não encontrada.');
        }
        if ($user->temItem($item['codigo'])) {
            return back()->with('aviso', 'Você já tem esta peça.');
        }
        if ($user->saldoPontos() < $item['preco']) {
            return back()->with('aviso', "Faltam pontos: {$item['nome']} custa {$item['preco']} pontos.");
        }

        // Registra a compra e já veste a peça.
        $user->compras()->create(['item' => $item['codigo'], 'preco' => $item['preco']]);
        $camada = $item['camada'];
        $user->$camada = $item['codigo'];
        $user->save();

        return redirect()->route('vestuario')->with('aviso', "Você comprou {$item['nome']}! Já está vestindo.");
    }
}
