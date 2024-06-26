<?php

namespace App\Casts;

use App\Models\Site;
use App\Models\SiteContentContent;
use App\Utils\HtmlHelper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ContentCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        return empty($value) ? $value : SiteContentContent::fromArray(json_decode($value, true));
    }

    public function set($model, string $key, $value, array $attributes)
    {
        /**@var Site $model * */
        if ($value instanceof SiteContentContent) {
            $valueToEncode = $value->toArray();
        } elseif (is_array($value)) {
            $valueToEncode = $value;
        } else {
            $valueToEncode = HtmlHelper::parseTags($value,$model);
        }

        return json_encode($valueToEncode);
    }

}
