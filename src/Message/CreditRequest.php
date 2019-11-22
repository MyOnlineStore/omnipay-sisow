<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Credit AfterPay, Focum or Klarna payments
 */
class CreditRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/CreditInvoiceRequest';

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

        // Only full refunds are supported with afterpay
        if ('afterpay' !== $this->getPaymentMethod() &&
            null !== $amount = $this->getAmountInteger()
        ) {
            $data['amount'] = $amount;
            $data['tax'] = 2100;
            $data['exclusive'] = false;
            $data['description'] = \sprintf('Refund %01.2f', $amount / 100);
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

        return $this->response = new CreditResponse(
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
