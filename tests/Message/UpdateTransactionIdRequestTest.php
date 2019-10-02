<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class UpdateTransactionIdRequestTest extends TestCase
{
    /**
     * @var UpdateTransactionIdRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new UpdateTransactionIdRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setMerchantId('123');
        $this->request->setMerchantKey('456');
        $this->request->setTransactionReference('789');
        $this->request->setTransactionId('foo');
        $this->request->setNewTransactionId('bar');
    }

    public function testGetData()
    {
        self::assertSame(
            [
                'trxid' => '789',
                'old' => 'foo',
                'new' => 'bar',
                'merchantid' => '123',
                'sha1' => sha1('789foobar123456'),
            ],
            $this->request->getData()
        );
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('UpdateTransactionIdSuccess.txt');

        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('bar', $response->getTransactionId());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('UpdateTransactionIdError.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getTransactionId());
    }
}
