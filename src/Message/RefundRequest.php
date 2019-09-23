<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/RefundRequest';

    /**
     * @inheritDoc
     */
    protected function generateSignature()
    {
        return sha1($this->getTransactionReference() . $this->getMerchantId() . $this->getMerchantKey());
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'merchantId', 'merchantKey');

        $data = [
            'trxid' => $this->getTransactionReference(),
            'merchantid' => $this->getMerchantId(),
            'sha1' => $this->generateSignature(),
        ];

        if (null !== $amount = $this->getAmountInteger()) {
            $data['amount'] = $amount;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $endpoint = $this->endpoint;

        if ($this->getTestMode()) {
            $endpoint .= '?test=true';
        }

        return $this->response = new RefundResponse(
            $this,
            $this->parseXmlResponse(
                $this->httpClient->request(
                    'POST',
                    $endpoint,
                    [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    http_build_query($data)
                )
            )
        );
    }
}
