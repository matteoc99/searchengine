<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @class Site
 * @property int         id
 * @property string      url_hash
 * @property string      url
 * @property string      description
 * @property string      author
 * @property string      title
 * @property string      keywords
 * @property string      canonical
 * @property SiteStatus  status
 * @property SiteStage   stage
 * @property int         http_code
 *
 * @property SiteContent $content
 */
class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_hash',
        'url',
        'description',
        'author',
        'title',
        'keywords',
        'canonical',
        'status',
        'stage',
        'http_code',
    ];

    protected $casts = [
        'status' => SiteStatus::class,
        'stage'  => SiteStage::class,
    ];

    public function toSearchableArray()
    {
        return Arr::only($this->attributesToArray(), [
            "url",
            "description",
            "author",
            "title",
        ]);
    }

    public function content()
    {
        return $this->hasOne(SiteContent::class);
    }
}
