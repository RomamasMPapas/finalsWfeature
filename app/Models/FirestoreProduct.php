<?php
namespace App\Models;

use App\Models\FirestoreModel;

class FirestoreProduct extends FirestoreModel
{
    public function __construct()
    {
        parent::__construct('products');
    }
}
?>
