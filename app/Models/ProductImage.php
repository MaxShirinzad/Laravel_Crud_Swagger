<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductImage
 *
 * @property-read Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 */
class ProductImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function imagePublicLink(): ?string
    {
        if ($this->name){
            return url('/') . '/productImages/' . $this->id . '/' . $this->name ?? null;
        }
        return null;
    }


}
