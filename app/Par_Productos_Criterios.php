<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Par_Productos_Criterios extends Model
{
    protected $table = 'par__productos__criterios';
    protected $primaryKey = 'idcriterio';
    protected $fillable = ['idprodparam','valornumerico','valorporcentual','idaux'];
}
