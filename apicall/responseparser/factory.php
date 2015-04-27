<?php

require_once 'abstract.php';
/**
 * ResponseParser Factory
 *
 * @author Kántor András
 * @since 2013.02.22. 14:56
 */
class ResponseParserFactory
{
    /**
     * @param $contentType
     * @return XmlResponseParser
     */
    public function createParser($contentType)
    {
        switch ($contentType) {
            case 'application/xml':
                require_once 'xml.php';
                return new XmlResponseParser();
            case 'application/json':
                require_once 'json.php';
                return new JsonResponseParser();
        }

        return false;
    }
}
