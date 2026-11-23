<?php

namespace App\Models;

use App\Support\Pinguim;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

/*
 * MODEL User -> tabela "users"
 *
 * Estende "Authenticatable" (e não Model) porque é o usuário que faz login:
 * é isso que permite usar Auth::attempt(), Auth::user(), o middleware 'auth' etc.
 *
 * Também guarda as REGRAS de pontos, sequência e conquistas (RF17, RF18, RF19),
 * as permissões dos 3 tipos de usuário (aluno, professor, admin) e as opções de acessibilidade.
 */
class User extends Authenticatable
{
    use Notifiable;

    // Regras do jogo num lugar só, para ficar fácil de mudar.
    const PONTOS_POR_ACERTO = 10;   // RF18
    const NOTA_MINIMA = 60;         // RF29: % de acertos para ser aprovado na lição

    // RF14: opções de nível. Chave = o que vai pro banco; valor = o que aparece na tela.
    const NIVEIS = [
        'iniciante'     => 'Iniciante: estou começando agora',
        'intermediario' => 'Intermediário: já programei um pouco',
        'avancado'      => 'Avançado: já programo com frequência',
    ];

    // Os 3 tipos de usuário (coluna "tipo").
    const TIPOS = [
        'aluno'     => 'Aluno',
        'professor' => 'Professor',
        'admin'     => 'Administrador',
    ];

    // Opções de tamanho da fonte (tela Configurações > Acessibilidade).
    const TAMANHOS_FONTE = [
        'normal'       => 'Normal',
        'grande'       => 'Grande',
        'muito_grande' => 'Muito grande',
    ];

    protected $fillable = [
        'tipo', 'nome', 'nome_usuario', 'email', 'password', 'foto',
        'cor_corpo', 'cor_barriga', 'cor_detalhe', 'cor_fundo',
        'roupa', 'pescoco', 'oculos', 'chapeu',
        'linguagem_id', 'nivel', 'meta_diaria',
        'pontos', 'sequencia', 'ultimo_estudo',
        'tamanho_fonte', 'alto_contraste', 'destacar_links',
    ];

    // A senha nunca aparece se o usuário for convertido em array/JSON.
    protected $hidden = ['password'];

    // Conversões automáticas de tipo.
    protected $casts = [
        'ultimo_estudo'  => 'date',     // vira um objeto de data (Carbon), com isToday(), isYesterday()...
        'alto_contraste' => 'boolean',  // 0/1 do banco vira false/true
        'destacar_links' => 'boolean',
    ];

    // ---------------- Relacionamentos ----------------

    public function linguagem()
    {
        return $this->belongsTo(Linguagem::class);
    }

    public function progressos()
    {
        return $this->hasMany(Progresso::class);
    }

    // N:N pela tabela pivô conquista_user. withTimestamps() preenche created_at (data em que ganhou).
    public function conquistas()
    {
        return $this->belongsToMany(Conquista::class)->withTimestamps();
    }

    // Peças exclusivas do vestuário que o usuário comprou.
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    // ---------------- Vestuário ----------------

    // Pontos que ainda dá para gastar: pontos acumulados - o que já gastou nas compras.
    public function saldoPontos()
    {
        return $this->pontos - $this->compras()->sum('preco');
    }

    // O usuário pode vestir esta peça? Peças grátis: sim. Exclusivas: só se comprou.
    public function temItem($codigo)
    {
        $item = Pinguim::item($codigo);
        if ($item === null) {
            return false;
        }
        if ($item['preco'] === 0) {
            return true;
        }
        return $this->compras()->where('item', $codigo)->exists();
    }

    // Cursos da comunidade criados por este usuário (quando ele é professor).
    public function cursosCriados()
    {
        return $this->hasMany(Curso::class, 'professor_id');
    }

    // ---------------- Tipos de usuário e permissões ----------------

    public function ehAdmin()
    {
        return $this->tipo === 'admin';
    }

    // Professor e admin podem usar a área do professor.
    public function ehProfessor()
    {
        return $this->tipo === 'professor' || $this->tipo === 'admin';
    }

    // Pode editar/excluir este curso? Só o professor dono ou um admin. Cursos oficiais ninguém edita pela tela.
    public function podeEditar(Curso $curso)
    {
        if ($curso->professor_id === null) {
            return false;
        }
        return $this->ehAdmin() || $curso->professor_id == $this->id;
    }

    // ---------------- Acessibilidade ----------------

    /*
     * Classes CSS que o layout coloca na tag <html> conforme as escolhas do usuário.
     * Ex.: "fonte-grande contraste" -> o public/css/app.css muda o tamanho do texto e as cores.
     */
    public function classesAcessibilidade()
    {
        $classes = 'fonte-' . $this->tamanho_fonte;   // fonte-normal, fonte-grande ou fonte-muito_grande
        if ($this->alto_contraste) {
            $classes .= ' contraste';
        }
        if ($this->destacar_links) {
            $classes .= ' links-destacados';
        }
        return $classes;
    }

    // E-mail parcialmente escondido: "ana.silva@gmail.com" -> "a*******a@gmail.com" (recuperação pelo nome de usuário)
    public function emailMascarado()
    {
        $partes = explode('@', $this->email);   // ["ana.silva", "gmail.com"]
        $nome = $partes[0];
        $escondido = substr($nome, 0, 1) . str_repeat('*', max(strlen($nome) - 2, 1)) . substr($nome, -1);
        return $escondido . '@' . $partes[1];
    }

    // ---------------- Regras ----------------

    /*
     * RF19: sequência que vale HOJE.
     * Se o último estudo foi hoje ou ontem, a sequência continua; senão, foi quebrada (zero).
     */
    public function sequenciaAtual()
    {
        if ($this->ultimo_estudo && ($this->ultimo_estudo->isToday() || $this->ultimo_estudo->isYesterday())) {
            return $this->sequencia;
        }
        return 0;
    }

    /*
     * RF19: chamada quando o usuário é aprovado numa lição.
     *   estudou hoje -> nada muda | estudou ontem -> +1 | outro caso -> recomeça em 1
     */
    public function registrarEstudo()
    {
        if ($this->ultimo_estudo && $this->ultimo_estudo->isToday()) {
            return;
        }
        if ($this->ultimo_estudo && $this->ultimo_estudo->isYesterday()) {
            $this->sequencia = $this->sequencia + 1;
        } else {
            $this->sequencia = 1;
        }
        $this->ultimo_estudo = now();
        $this->save();
    }

    // RF20: quantas lições o usuário concluiu hoje (para comparar com a meta diária).
    public function licoesHoje()
    {
        return $this->progressos()->where('aprovado', true)->whereDate('concluido_em', today())->count();
    }

    /*
     * RF17: dá ao usuário as conquistas que ele alcançou e ainda não tinha.
     * Compara os números do usuário com a "meta" de cada conquista.
     * Devolve as conquistas novas (para a tela mostrar "Você ganhou...").
     */
    public function verificarConquistas()
    {
        $numeros = [
            'licoes'    => $this->progressos()->where('aprovado', true)->count(),
            'sequencia' => $this->sequencia,
            'pontos'    => $this->pontos,
        ];

        // Conquistas que o usuário AINDA NÃO tem.
        $jaTem = $this->conquistas()->pluck('conquistas.id');
        $faltando = Conquista::whereNotIn('id', $jaTem)->get();

        $novas = [];
        foreach ($faltando as $conquista) {
            if ($numeros[$conquista->tipo] >= $conquista->meta) {
                $this->conquistas()->attach($conquista->id);   // INSERT na tabela conquista_user
                $novas[] = $conquista;
            }
        }
        return $novas;
    }

    /*
     * RF03: e-mail de recuperação de senha.
     * O Laravel chama este método quando usamos Password::sendResetLink().
     * Escrevemos nossa própria versão para o e-mail sair em português.
     * Com MAIL_MAILER=log (no .env), o e-mail vai para storage/logs/laravel.log.
     */
    public function sendPasswordResetNotification($token)
    {
        $link = route('password.reset', ['token' => $token, 'email' => $this->email]);

        $texto = "Olá, {$this->nome}!\n\n"
            . "Para criar uma senha nova no TechnoPenguin, acesse o link abaixo:\n"
            . "$link\n\n"
            . "O link vale por 60 minutos. Se você não pediu isso, ignore este e-mail.";

        Mail::raw($texto, function ($mensagem) {
            $mensagem->to($this->email)->subject('TechnoPenguin - recuperar senha');
        });
    }
}
