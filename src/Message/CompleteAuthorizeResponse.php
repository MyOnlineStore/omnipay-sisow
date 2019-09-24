<?php

namespace Omnipay\Sisow\Message;

class CompleteAuthorizeResponse extends CompletePurchaseResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->transaction->status) &&
            'Reservation' === (string) $this->data->transaction->status;
    }
}
