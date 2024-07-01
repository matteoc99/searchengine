<?php

namespace App\Models;

use App\Casts\ContentCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class SiteContent
 * @property int                site_id
 * @property SiteContentContent content
 */
class SiteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'content',
    ];

    protected $casts = [
        'content' => ContentCast::class,
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function toSearchableArray()
    {
        return [
            "content" => $this->content->toArray(),
        ];
    }
}
