<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property string $Title
 * @property string|null $Desc
 * @property int $Price
 * @property string|null $slug
 * @property int $viewCount
 * @property int $status
 * @property int $IsImage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @method static ProductFactory factory(...$parameters)
 * @method static Builder|Product findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDesc($value)
 * @method static Builder|Product whereGroupId($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereIsImage($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereUserId($value)
 * @method static Builder|Product whereViewCount($value)
 */
//class Product extends Model implements HasMedia
class Product extends Model
{
    use HasFactory;
    use Sluggable;
//    use InteractsWithMedia;

    protected $guarded = ['id'];

//    public $timestamps =false;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'Title'
            ]
        ];
    }

    public function path(): string
    {
        return "/products/$this->slug";
    }

    public function getRouteKeyName(): string // انجام جستجو بر اساس اسلاگ بجای آی دی
    {
        return 'slug';
    }

    //----------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imageShow(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }


}

