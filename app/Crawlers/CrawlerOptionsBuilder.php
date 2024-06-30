<?php

namespace App\Crawlers;


use App\Models\SiteStage;

class CrawlerOptionsBuilder
{
    private string    $url;
    private SiteStage $stage;
    private int       $limit;
    private int       $depth;
    private bool      $force_crawl;

    private function __construct(string $url)
    {
        $this->url         = $url;
        $this->stage       = SiteStage::PLAIN_HTTP;
        $this->limit       = 100;
        $this->depth       = 10;
        $this->force_crawl = false;
    }

    public static function create(string $url): self
    {
        return new self($url);
    }

    public function withStage(SiteStage $stage): self
    {
        $this->stage = $stage;
        return $this;
    }

    public function withLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setForce(bool $force): self
    {
        $this->force_crawl = $force;
        return $this;
    }

    public function withDepth(int $depth): self
    {
        $this->depth = $depth;
        return $this;
    }

    public function build(): CrawlerOptions
    {
        return new CrawlerOptions(
            $this->url,
            $this->stage,
            $this->limit,
            $this->depth,
            $this->force_crawl
        );
    }
}
