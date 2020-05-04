<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

class FetchIssuerUrlResponse extends BaseAbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return !empty($this->data->transaction->issuerurl);
    }

    /**
     * @return string|null
     */
    public function getIssuerUrl()
    {
        if (!empty($this->data->transaction->issuerurl)) {
            return urldecode($this->data->transaction->issuerurl);
        }

        return null;
    }
}
