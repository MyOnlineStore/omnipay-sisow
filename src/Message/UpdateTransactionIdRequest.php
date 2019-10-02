<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class UpdateTransactionIdRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/AdjustPurchaseId';

    /**
     * @inheritDoc
     */
    protected function generateSignature()
    {
        return sha1(
            $this->getTransactionReference() .
            $this->getTransactionId() .
            $this->getNewTransactionId() .
            $this->getMerchantId() .
            $this->getMerchantKey()
        );
    }

    public function getNewTransactionId()
    {
        return $this->getParameter('newTransactionId');
    }

    public function setNewTransactionId($transactionId)
    {
        return $this->setParameter('newTransactionId', $transactionId);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'transactionReference',
            'transactionId',
            'newTransactionId',
            'merchantId',
            'merchantKey'
        );

        return [
            'trxid' => $this->getTransactionReference(),
            'old' => $this->getTransactionId(),
            'new' => $this->getNewTransactionId(),
            'merchantid' => $this->getMerchantId(),
            'sha1' => $this->generateSignature(),
        ];
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

        return $this->response = new UpdateTransactionIdResponse(
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
