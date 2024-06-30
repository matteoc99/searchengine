<?php

namespace App\Services;

use App\Models\Site;
use App\Utils\UrlHelper;
use Illuminate\Database\Eloquent\Model;

class SiteService extends BaseService
{

    public function create($data)
    {
        $data['url_hash'] = UrlHelper::formatAndHashUrl($data['url']);
        return parent::create($data);
    }

    public function update(Model $model, $data, &$changes = null): Model
    {
        return parent::update($model, $data, $changes);
    }



    protected function model(): string
    {
        return Site::class;
    }

}
