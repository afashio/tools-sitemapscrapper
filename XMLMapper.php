<?php
/**
 * @author afashio
 */
require_once 'UrlHelper.php';

class XMLMapper
{
    /**
     * @var array[]
     */
    private $brands = [
        'tools' => [],
        'dewalt' => [],
        'karcher' => [],
        'makita' => [],
        'bosch' => [],
        'metabo' => [],
        'hyundai' => [],
        'dremel' => []
    ];
    /**
     * @var \SimpleXMLElement
     */
    private $xml;
    /**
     * @var null|string
     */
    private $key;

    /**
     * XMLMapper constructor.
     *
     * @param \SimpleXMLElement $xml
     */
    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    /**
     * Parse Xml
     */
    public function parse(): void
    {
        foreach ($this->xml as $xmlUrl) {
            if ($this->isAppropriate($xmlUrl)) {
                $this->setBrand($xmlUrl);
            }
        }
    }

    /**
     * @param \SimpleXMLElement $xmlUrl
     *
     * @return boolean
     */
    public function isAppropriate($xmlUrl): bool
    {
        $path = parse_url($xmlUrl->loc, PHP_URL_PATH);
        if (!UrlHelper::checkUrl($path)) {
            return false;
        }
        if ($path && $path !== '/') {
            $array = array_filter(explode('/', $path));
            $this->key = reset($array);
            if ($this->key === 'tools' && !preg_match('/\/$/', $xmlUrl->loc)) {
                return false;
            }
            if (isset($this->brands[$this->key])) {
                return true;
            }
        }
        $this->key = 'tools';

        return true;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        foreach ($this->brands as $brand => $value) {
            $this->brands[$brand] = $this->getUniqueLocs($value);
        }

        return $this->brands;
    }

    /**
     * @param array $brand
     *
     * @return array
     */
    private function getUniqueLocs(array $brand): array
    {
        $tmp = $key_array = [];
        $i = 0;

        foreach ($brand as $val) {
            if (!in_array($val['loc'], $key_array)) {
                $key_array[$i] = $val['loc'];
                $tmp[$i] = $val;
            }
            $i++;
        }

        return $tmp;
    }

    /**
     * @param \SimpleXMLElement $xmlUrl
     */
    private function setBrand($xmlUrl): void
    {
        $url = parse_url($xmlUrl->loc);
        $url = $url['scheme'] . '://' . $url['host'] . $url['path'];
        $this->brands[$this->key][] = [
            'loc' => $url,
            'priority' => (string)$xmlUrl->priority,
        ];
    }


}