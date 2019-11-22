<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class CreditRequestTest extends TestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new CreditRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setMerchantId('123');
        $this->request->setMerchantKey('456');
        $this->request->setTransactionReference('789');
    }

    public function testGetData()
    {
        self::assertSame(
            [
                'trxid' => '789',
                'merchantid' => '123',
                'sha1' => sha1('789123456'),
            ],
            $this->request->getData()
        );
    }

    public function testGetDataPartial()
    {
        $this->request->setAmountInteger(121);

        self::assertSame(
            [
                'trxid' => '789',
                'merchantid' => '123',
                'sha1' => sha1('789123456'),
                'amount' => 121,
                'tax' => 2100,
                'exclusive' => false,
                'description' => 'Refund 1.21',
            ],
            $this->request->getData()
        );
    }

    public function testGetDataPartialForAfterpay()
    {
        $this->request->setPaymentMethod('afterpay');
        $this->request->setAmountInteger(121);

        self::assertSame(
            [
                'trxid' => '789',
                'merchantid' => '123',
                'sha1' => sha1('789123456'),
            ],
            $this->request->getData()
        );
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreditSuccess.txt');

        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('123456', $response->getInvoiceNumber());
        self::assertEquals('987654', $response->getDocumentId());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('CreditError.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getInvoiceNumber());
        self::assertNull($response->getDocumentId());
    }
}
