<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "master_items";
    protected $fillable = ['kode', 'nama', 'harga_beli', 'laba', 'supplier', 'jenis', 'foto_product'];

    public function kategori()
    {
        return $this->belongsToMany(
            KategoriItems::class,
            'kategori_item_master_item',
            'master_item_id',
            'kategori_item_id'
        );
    }
}
