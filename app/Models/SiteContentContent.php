<?php

namespace App\Models;

class SiteContentContent
{
    public array $headings;

    public array $links;

    public array $image_alts;

    public array $articles;

    public array $sections;

    public array $asides;

    public array $navs;

    public array $tables;

    public array $lists;

    public function __construct(
        array $headings = [],
        array $links = [],
        array $image_alts = [],
        array $articles = [],
        array $sections = [],
        array $asides = [],
        array $navs = [],
        array $tables = [],
        array $lists = []
    ) {
        $this->headings   = $headings;
        $this->links      = $links;
        $this->image_alts = $image_alts;
        $this->articles   = $articles;
        $this->sections   = $sections;
        $this->asides     = $asides;
        $this->navs       = $navs;
        $this->tables     = $tables;
        $this->lists      = $lists;
    }


    public static function fromArray(array $data): SiteContentContent
    {
        return new self(
            array_unique($data['headings'] ?? []),
            array_unique($data['links'] ?? []),
            array_unique($data['image_alts'] ?? []),
            array_unique($data['articles'] ?? []),
            array_unique($data['sections'] ?? []),
            array_unique($data['asides'] ?? []),
            array_unique($data['navs'] ?? []),
            array_unique($data['tables'] ?? []),
            array_unique($data['lists'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            'headings'   => $this->headings,
            'links'      => $this->links,
            'image_alts' => $this->image_alts,
            'articles'   => $this->articles,
            'sections'   => $this->sections,
            'asides'     => $this->asides,
            'navs'       => $this->navs,
            'tables'     => $this->tables,
            'lists'      => $this->lists,
        ];
    }
}
