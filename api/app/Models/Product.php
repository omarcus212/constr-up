<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'products';


    protected $fillable = [
        'name',
        'description',
        'brand',
        'price',
        'stock',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}
