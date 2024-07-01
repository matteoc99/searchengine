<?php

namespace App\Utils;

use App\Models\Site;
use App\Models\SiteContentContent;
use DOMDocument;

class HtmlHelper
{


    public static function parseTags(array|string $html, ?Site $site = null): array|null
    {
        if (empty($html) || is_array($html)) {
            return $html;
        }

        $doc = new DOMDocument();

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        @$doc->loadHTML($html);

        $data = [];

        $titles        = $doc->getElementsByTagName('title');
        $data['title'] = $titles->length > 0 ? $titles->item(0)->textContent : '';

        $metaTags = $doc->getElementsByTagName('meta');
        foreach ($metaTags as $meta) {
            $name = $meta->getAttribute('name');
            if ($name === 'description') {
                $data['description'] = $meta->getAttribute('content');
            } elseif ($name === 'keywords') {
                $data['keywords'] = $meta->getAttribute('content');
            } elseif ($name === 'author') {
                $data['author'] = $meta->getAttribute('content');
            }
        }

        $linkTags = $doc->getElementsByTagName('link');
        foreach ($linkTags as $link) {
            if ($link->getAttribute('rel') === 'canonical') {
                $data['canonical'] = $link->getAttribute('href');
                break;
            }
        }

        $data['headings'] = [];
        for ($i = 1; $i <= 6; $i++) {
            $headingTag                    = 'h' . $i;
            $tags                          = $doc->getElementsByTagName($headingTag);
            $data['headings'][$headingTag] = [];
            foreach ($tags as $tag) {
                $data['headings'][$headingTag][] = $tag->textContent;
            }
        }

        $contentTags = ['article', 'section', 'aside', 'nav'];
        foreach ($contentTags as $tagName) {
            $tags           = $doc->getElementsByTagName($tagName);
            $data[$tagName] = [];
            foreach ($tags as $tag) {
                $data[$tagName][] = $tag->textContent;
            }
        }

        $data['links'] = [];
        $anchorTags    = $doc->getElementsByTagName('a');
        foreach ($anchorTags as $tag) {
            $href = $tag->getAttribute('href');
            if (!$site?->url || self::isCrossSiteLink($site?->url, $href)) {
                $parsedUrl = parse_url($href);
                if (isset($parsedUrl['host'])) {
                    $domain          = $parsedUrl['host'];
                    $data['links'][] = $domain;
                }
            }
        }

        $data['image_alts'] = [];
        $imageTags          = $doc->getElementsByTagName('img');
        foreach ($imageTags as $tag) {
            $data['image_alts'][] = $tag->getAttribute('alt');
        }

        $data['tables'] = [];
        $tableTags      = $doc->getElementsByTagName('table');
        foreach ($tableTags as $table) {
            $rows      = $table->getElementsByTagName('tr');
            $tableData = [];
            foreach ($rows as $row) {
                $cells   = $row->getElementsByTagName('td');
                $rowData = [];
                foreach ($cells as $cell) {
                    $rowData[] = $cell->textContent;
                }
                $tableData[] = $rowData;
            }
            $data['tables'][] = $tableData;
        }

        $data['lists'] = [];
        $listTags      = ['ul', 'ol'];
        foreach ($listTags as $listTag) {
            $lists = $doc->getElementsByTagName($listTag);
            foreach ($lists as $list) {
                $items    = $list->getElementsByTagName('li');
                $listData = [];
                foreach ($items as $item) {
                    $listData[] = $item->textContent;
                }
                $data['lists'][$listTag][] = $listData;
            }
        }
        return $data;
    }

    public static function parseContent(array|string $html, ?Site $site = null): SiteContentContent|null
    {


        return SiteContentContent::fromArray(is_array($html) ? $html : self::parseTags($html, $site));
    }

    private static function isCrossSiteLink(string $url, string $href): bool
    {
        $href = strtok($href, '#');
        $href = strtok($href, '?');

        $parsedUrl = parse_url($href);
        if (!isset($parsedUrl['host'])) {
            return false;
        }

        $currentHost = parse_url($url, PHP_URL_HOST);

        return $parsedUrl['host'] !== $currentHost;
    }

}
