<?php

namespace Omnipay\Sisow\Message;

class UpdateTransactionIdResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->adjustpurchaseid->purchaseid);
    }

    /**
     * @return string|null
     */
    public function getTransactionId()
    {
        return isset($this->data->adjustpurchaseid->purchaseid) ?
            (string) $this->data->adjustpurchaseid->purchaseid :
            null;
    }
}
