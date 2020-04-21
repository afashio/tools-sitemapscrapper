<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 28.02.2018
 * Time: 12:31
 *
 * @property  DOMDocument $xml
 * @property  DOMDocument $rootNode
 */

class SiteMapCreator
{

    private const FREQ = 'weekly';

    /**
     * @var \SimpleXMLElement
     */
    private $xml;
    /**
     * @var \SimpleXMLElement
     */
    private $rootNode;
    /**
     * @var array
     */
    private $pagePriorMap = [
        '0.4' => 1,
        '0.5' => 1,
        '0.6' => 0.9,
        '0.7' => 0.8,
        '0.8' => 0.6,
        '0.9' => 0.6,
        '1' => 0.6,
        '1.0' => 0.6,
    ];
    /**
     * @var array
     */
    private $pages;
    /**
     * @var string
     */
    private $key;


    /**
     * Sitemap constructor.
     *
     * @param $data
     */
    public function __construct($data, $key)
    {
        $this->pages = $data;
        $this->key = $key;
    }

    /**
     * @return int|null
     */
    public function createSitemap(): ?int
    {
        $this->setXml();
        $this->setRootNode();
        $this->addPages();
        $this->xml->formatOutput = true;

        return $this->xml->save("ready/sitemap-{$this->key}.xml");
    }

    /**
     * @return void
     */
    private function setXml(): void
    {
        $this->xml = new DOMDocument('1.0', 'utf-8');
    }

    /**
     * @return void
     */
    private function setRootNode(): void
    {
        $this->rootNode = $this->xml->appendChild($this->xml->createElement('urlset'));
        $node = $this->rootNode->appendChild($this->xml->createAttribute('xmlns'));
        $node->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    }

    /**
     * @param string $alias
     * @param string $priority
     *
     * @return void
     */
    private function addElement($alias, $priority): void
    {
        $element = $this->rootNode->appendChild($this->xml->createElement('url'));
        $element->appendChild($this->xml->createElement('loc', $alias));
        $element->appendChild($this->xml->createElement('lastmod', date('Y-m-d')));
        $element->appendChild($this->xml->createElement('changefreq', self::FREQ));
        $element->appendChild($this->xml->createElement('priority', $priority));
    }

    /**
     * @return void
     */
    private function addPages(): void
    {
        foreach ($this->pages as $page) {
            $this->addElement($page['loc'], $this->pagePriorMap[$page['priority']]);
        }
    }

}