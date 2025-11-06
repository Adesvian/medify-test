<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriItems extends Model
{
    use HasFactory;

    protected $table = 'kategori_items';
    protected $fillable = ['kode', 'nama'];

    public function master()
    {
        return $this->belongsToMany(
            MasterItem::class,
            'kategori_item_master_item',
            'kategori_item_id',
            'master_item_id'
        );
    }
}
