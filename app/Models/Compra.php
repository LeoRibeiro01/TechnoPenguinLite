<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Compra -> tabela "compras"
 * Uma peça exclusiva do vestuário comprada por um usuário.
 */
class Compra extends Model
{
    protected $fillable = ['user_id', 'item', 'preco'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
