<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class FetchIssuerUrlRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/IssuerUrlRequest';

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'merchantId', 'merchantKey');

        return [
            'trxid' => $this->getTransactionReference(),
            'merchantid' => $this->getMerchantId(),
            'sha1' => $this->generateSignature(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {

        return $this->response = new FetchIssuerUrlResponse(
            $this,
            $this->parseXmlResponse(
                $this->httpClient->request(
                    'GET',
                    \sprintf('%s?%s', $this->endpoint, http_build_query($data))
                )
            )
        );
    }

    /**
     * @inheritDoc
     */
    protected function generateSignature()
    {
        return sha1($this->getTransactionReference() . $this->getMerchantId() . $this->getMerchantKey());
    }
}
