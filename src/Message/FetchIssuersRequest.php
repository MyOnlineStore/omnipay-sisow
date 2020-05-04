<?php

namespace Omnipay\Sisow\Message;

class FetchIssuersRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/DirectoryRequest';

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $endpoint = $this->endpoint;
        if ($this->getTestMode()) {
            $endpoint .= '?test=true';
        }

        $httpResponse = $this->httpClient->request('GET', $endpoint);

        return $this->response = new FetchIssuersResponse($this, $this->parseXmlResponse($httpResponse));
    }
}
