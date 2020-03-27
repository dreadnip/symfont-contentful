<?php

namespace App\Service;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Contentful\RichText\Node\Document;
use Contentful\RichText\Renderer;

class ContentfulClient extends Client
{
    /**
     * @var Renderer
     */
    private $renderer;

    public function __construct(
        string $contentfulId,
        string $contentfulToken
    )
    {
        parent::__construct($contentfulToken, $contentfulId);

        $this->renderer = new Renderer();
    }

    public function getPages()
    {
        return $this->getType('page');
    }

    public function getProducts()
    {
        $query = new Query();
        $query->setContentType('product');

        return $this->getEntries($query);
    }

    private function parse(ResourceArray $entries): ResourceArray
    {
        foreach ($entries as &$entry) {
            foreach ($entry->all() as &$field) {
                if ($field instanceof Document) {
                    $field->parsed= $this->renderer->render($field);
                }
            }
        }

        return $entries;
    }

    private function getType(string $type): ?ResourceArray
    {
        $query = new Query();
        $query->setContentType($type);
        $entries = $this->getEntries($query);
        return $this->parse($entries);
    }
}