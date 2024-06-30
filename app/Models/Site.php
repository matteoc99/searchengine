<?php

namespace App\Models;

use App\Casts\ContentCast;
use App\Utils\UrlHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Site
 * @property string      url_hash
 * @property string      url
 * @property SiteContent content
 * @property SiteStatus  status
 * @property SiteStage   stage
 * @property int         http_code
 *
 */
class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_hash',
        'url',
        'content',
        'status',
        'stage',
        'http_code'
    ];

    protected $casts = [
        'content' => ContentCast::class,
        'status'  => SiteStatus::class,
        'stage'   => SiteStage::class,
    ];

    public function toSearchableArray()
    {
        return [
            'url'     => $this->url,
            'content' => $this->content->toArray(),
        ];
    }


    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $value,
            set: fn(string $value) => UrlHelper::formatUrl($value),
        );
    }
}
