<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Produto do catálogo privado de um usuário.
 *
 * Cada registro pertence a um usuário específico, garantindo isolamento entre locatários.
 */
class Product extends Model
{
    protected $fillable = ['nome', 'descricao', 'preco', 'quantidade', 'foto', 'user_id'];

    /**
     * Usuário proprietário do produto.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
