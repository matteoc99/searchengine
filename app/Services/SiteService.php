<?php

namespace App\Services;

use App\Models\Site;
use App\Utils\HtmlHelper;
use App\Utils\UrlHelper;
use Illuminate\Database\Eloquent\Model;

class SiteService extends BaseService
{
    protected function model(): string
    {
        return Site::class;
    }

    public function create($data)
    {
        $data['url_hash'] = UrlHelper::formatAndHashUrl($data['url']);
        return parent::create($data);
    }

    public function update(Model $model, $data, &$changes = null): Model
    {
        return parent::update($model, $data, $changes);
    }


    public function updateOrCreate(array $unique, array $data = []): Model
    {
        if (array_key_exists('url', $data)) {
            $data['url'] = UrlHelper::formatUrl($data['url']);
        }
        if (array_key_exists('url', $unique)) {
            $unique['url'] = UrlHelper::formatUrl($unique['url']);
        }

        if (array_key_exists('content', $data)) {
            $parsedContent       = HtmlHelper::parseTags($data['content']) ?? [];
            $data['title']       = $parsedContent['title'] ?? null;
            $data['description'] = $parsedContent['description'] ?? null;
            $data['keywords']    = $parsedContent['keywords'] ?? null;
            $data['author']      = $parsedContent['author'] ?? null;
            $data['canonical']   = $parsedContent['canonical'] ?? null;
        }

        /**@var Site $site * */
        $site = parent::updateOrCreate($unique, $data);

        if (array_key_exists('content', $data)) {
            SiteContentService::instance()->updateOrCreate(['site_id' => $site->id], ['content' => HtmlHelper::parseContent($data['content'])]);
        }

        return $site->fresh();
    }


}
