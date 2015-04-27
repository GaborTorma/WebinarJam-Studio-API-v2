<?php
require_once 'abstract.php';

/**
 * Xml Response parser
 *
 * @author Kántor András
 * @since 2013.02.22. 14:32
 */
class XmlResponseParser extends ResponseParser
{
    /**
     * @return array
     */
    public function parse()
    {
        $xml = simplexml_load_string($this->response, null, LIBXML_NOCDATA);
        $result = $this->xmlToArray($xml);
        return $result;
    }

    /**
     * @param SimpleXMLElement|string $xml
     * @return array
     */
    protected function xmlToArray($xml)
    {
        if (is_string($xml)) {
            return $xml;
        }

        //Akkor SimpleXMLElement
        $children = (array)$xml->children();
        $data = array();
        if (count($children) > 0) {
            foreach ($children as $key => $child) {
                if (is_array($child) && count($child) > 0 && !$this->isAssociative($child)) {
                    foreach ($child as $cKey => $c) {
                        $data[$key][$cKey] = $this->xmlToArray($c);
                    }
                } else {
                    $data[$key] = $this->xmlToArray($child);
                }
            }
            return $data;
        } else {
            return (string)$xml;
        }
    }

    /**
     * Megvizsgálja, hogy a kapott tömb asszociatív-e,
     * tehát az értékeknek vannak-e kulcsok megadva
     *
     * @param $node
     * @return bool
     */
    protected function isAssociative($node)
    {
        return array_keys($node) !== range(0, count($node) - 1);
    }
}
