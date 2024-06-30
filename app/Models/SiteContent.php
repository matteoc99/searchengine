<?php

namespace App\Models;

class SiteContent
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $keywords;

    /**
     * @var array
     */
    public $headings;

    /**
     * @var array
     */
    public $links;

    /**
     * @var array
     */
    public $image_alts;

    /**
     * @var string
     */
    public $author;

    /**
     * @var string
     */
    public $canonical;

    /**
     * @var array
     */
    public $articles;

    /**
     * @var array
     */
    public $sections;

    /**
     * @var array
     */
    public $asides;

    /**
     * @var array
     */
    public $navs;

    /**
     * @var array
     */
    public $tables;

    /**
     * @var array
     */
    public $lists;

    /**
     * Content constructor.
     *
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param array  $headings
     * @param array  $links
     * @param array  $image_alts
     * @param string $author
     * @param string $canonical
     * @param array  $articles
     * @param array  $sections
     * @param array  $asides
     * @param array  $navs
     * @param array  $tables
     * @param array  $lists
     */
    public function __construct(
        $title = '',
        $description = '',
        $keywords = '',
        $headings = [],
        $links = [],
        $image_alts = [],
        $author = '',
        $canonical = '',
        $articles = [],
        $sections = [],
        $asides = [],
        $navs = [],
        $tables = [],
        $lists = []
    ) {
        $this->title       = $title;
        $this->description = $description;
        $this->keywords    = $keywords;
        $this->headings    = $headings;
        $this->links       = $links;
        $this->image_alts  = $image_alts;
        $this->author      = $author;
        $this->canonical   = $canonical;
        $this->articles    = $articles;
        $this->sections    = $sections;
        $this->asides      = $asides;
        $this->navs        = $navs;
        $this->tables      = $tables;
        $this->lists       = $lists;
    }

    /**
     * Create a new instance from an array.
     *
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data)
    {
        return new self(
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['keywords'] ?? '',
            $data['headings'] ?? [],
            $data['links'] ?? [],
            $data['image_alts'] ?? [],
            $data['author'] ?? '',
            $data['canonical'] ?? '',
            $data['articles'] ?? [],
            $data['sections'] ?? [],
            $data['asides'] ?? [],
            $data['navs'] ?? [],
            $data['tables'] ?? [],
            $data['lists'] ?? []
        );
    }

    /**
     * Convert the instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title'       => $this->title,
            'description' => $this->description,
            'keywords'    => $this->keywords,
            'headings'    => $this->headings,
            'links'       => $this->links,
            'image_alts'  => $this->image_alts,
            'author'      => $this->author,
            'canonical'   => $this->canonical,
            'articles'    => $this->articles,
            'sections'    => $this->sections,
            'asides'      => $this->asides,
            'navs'        => $this->navs,
            'tables'      => $this->tables,
            'lists'       => $this->lists,
        ];
    }
}
