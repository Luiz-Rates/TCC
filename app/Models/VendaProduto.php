<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaProduto extends Model
{
    protected $fillable = ['venda_id', 'produto_id', 'quantidade', 'preco', 'total'];

    public function venda()
    {
        return $this->belongsTo(Sale::class, 'venda_id');
    }

    public function produto()
    {
        return $this->belongsTo(Product::class, 'produto_id');
    }
}
