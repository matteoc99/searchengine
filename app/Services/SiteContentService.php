<?php

namespace App\Services;

use App\Models\SiteContent;
use App\Utils\HtmlHelper;
use App\Utils\UrlHelper;
use Illuminate\Database\Eloquent\Model;

class SiteContentService extends BaseService
{
    protected function model(): string
    {
        return SiteContent::class;
    }

    public function create($data)
    {
        return parent::create($data);
    }

    public function update(Model $model, $data, &$changes = null): Model
    {
        return parent::update($model, $data, $changes);
    }


    public function updateOrCreate(array $unique, array $data = []): Model
    {
        return parent::updateOrCreate($unique, $data);
    }


}
