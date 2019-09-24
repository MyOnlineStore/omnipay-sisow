<?php

namespace Omnipay\Sisow\Message;

class CompleteAuthorizeRequest extends CompletePurchaseRequest
{
    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        return new CompleteAuthorizeResponse($this, parent::sendData($data)->getData());
    }
}
