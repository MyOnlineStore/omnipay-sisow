<?php

namespace Omnipay\Sisow\Message;

class AuthorizeResponse extends PurchaseResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->transaction->trxid);
    }
}
