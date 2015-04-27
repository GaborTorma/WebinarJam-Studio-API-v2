<?php

/**
 * ResponseParser
 *
 * @author Kántor András
 * @since 2013.02.22. 14:47
 */
abstract class ResponseParser
{
    /**
     * @var string
     */
    protected $response;

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    abstract public function parse();
}
