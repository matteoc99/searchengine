<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @class Site
 * @property string url
 * @property string content
 * @property array  headers
 *
 */
class Site extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'content', 'headers'];
    protected $casts    = [
        'headers' => 'array',
    ];

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $value,
            set: fn(string $value) => $this->parseTags($value),
        );
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $value,
            set: fn(string $value) => $this->formatUrl($value),
        );
    }

    public function toSearchableArray()
    {
        return [
            'url'      => $this->url,
            'content'  => $this->content,
            'metadata' => $this->metadata,
        ];
    }


    public function parseTags($value): false|string
    {
        if (empty($value)) return false;

        $html = new \Html2Text\Html2Text($value);

        return $html->getText();
    }

    private function formatUrl(string $value)
    {
        if (!Str::startsWith($value, ['http://', 'https://'])) {
            $value = 'https://' . $value;
        }

        return trim($value);
    }
}
