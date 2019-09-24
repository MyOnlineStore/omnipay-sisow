<?php

namespace Omnipay\Sisow\Message;

class AuthorizeRequest extends PurchaseRequest
{
    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        return new AuthorizeResponse($this, parent::sendData($data)->getData());
    }
}
