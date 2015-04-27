<?php

require_once 'response.php';
/**
 * Api Call
 *
 * @author Kántor András
 * @since 2013.02.22. 13:43
 */
class ApiCall
{
    /**
     * @var string
     */
    protected $response;

    /**
     * @var string
     */

    protected $format = 'json';
    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @throws Exception
     * @return Response
     */
    public function execute($method, $url, array $data = array())
    {
        $curlHandle = curl_init();
        $this->setUrl($curlHandle, $url);
        $this->setOptions($curlHandle);
		
        switch ($method) {
            case 'GET':
                $this->executeGet($curlHandle);
                break;
            case 'POST':
                $this->executePost($curlHandle, $data);
                break;
            case 'PUT':
                $this->executePut($curlHandle, $data);
                break;
            case 'DELETE':
                $this->executeDelete($curlHandle);
                break;
            default:
                throw new Exception('Invalid HTTP METHOD');
        }

        return $this->response;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @param $curlHandle
     * @param string $url
     */
    protected function setUrl($curlHandle, $url)
    {
        curl_setopt($curlHandle, CURLOPT_URL, $url);
    }

    /**
     * @param $curlHandle
     */
    protected function setOptions($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_HEADER, 1);
//        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Expect:"));
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Accept: application/" . $this->format));
    }

    /**
     * @param $curlHandle
     */
    protected function executeGet($curlHandle)
    {
        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     * @param array $data
     */
    protected function executePost($curlHandle, array $data)
    {
        $query = http_build_query($data);
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $query);

        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     * @param array $data
     */
    protected function executePut($curlHandle, array $data)
    {
        $query = http_build_query($data);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $query);

        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     */
    protected function executeDelete($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     * @return string
     */
    protected function doExecute($curlHandle)
    {
        ob_start();
        curl_exec($curlHandle);
        $content = ob_get_contents();
        ob_end_clean();

        list($headers, $responseBody) = explode("\r\n\r\n", $content, 2);
        $statusCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($curlHandle, CURLINFO_CONTENT_TYPE);

        preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $headers, $matches);
        $location = isset($matches[1]) ? $matches[1] : '';
        $this->response = new Response($statusCode, $contentType, $location, $responseBody);

        curl_close($curlHandle);
    }

    /**
     * @param bool $bool
     * @param string $errorMessage
     * @throws Exception
     */
    protected function ensure($bool, $errorMessage)
    {
        if (!$bool) {
            throw new Exception($errorMessage);
        }
    }
}
