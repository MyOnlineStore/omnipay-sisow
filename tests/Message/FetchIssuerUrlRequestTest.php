<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class FetchIssuerUrlRequestTest extends TestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchIssuerUrlRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchIssuerUrlSuccess.txt');

        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertInstanceOf(FetchIssuerUrlResponse::class, $response);
        self::assertEquals(
            'https://www.sisow.nl/Sisow/portal/Simulator.aspx?merchantid=1234&txid=5678&sha1=0987',
            $response->getIssuerUrl()
        );
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FetchIssuerUrlError.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
    }
}
