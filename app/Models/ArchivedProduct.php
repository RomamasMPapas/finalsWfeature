<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedProduct extends Model
{
    use HasFactory;

    protected $table = 'archived_products';

    protected $fillable = [
        'product_id',
        'name',
        'price',
        'category',
        'description',
        'gallery',
        'quantity',
    ];
}
