<?php

namespace Omnipay\Sisow;

use Omnipay\Sisow\Message\AuthorizeRequest;
use Omnipay\Sisow\Message\CaptureRequest;
use Omnipay\Sisow\Message\CompleteAuthorizeRequest;
use Omnipay\Sisow\Message\CreditRequest;
use Omnipay\Sisow\Message\FetchIssuerUrlRequest;
use Omnipay\Sisow\Message\FetchTransactionRequest;
use Omnipay\Sisow\Message\RefundRequest;
use Omnipay\Sisow\Message\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setShopId(0);
        $this->gateway->setMerchantId('0123456');
        $this->gateway->setMerchantKey('b36d8259346eaddb3c03236b37ad3a1d7a67cec6');
    }

    public function testFetchIssuers()
    {
        /** @var \Omnipay\Sisow\Message\FetchIssuersRequest $request */
        $request = $this->gateway->fetchIssuers();

        $this->assertInstanceOf('Omnipay\Sisow\Message\FetchIssuersRequest', $request);
        $this->assertNull($request->getData());
    }

    public function testPurchase()
    {
        /** @var \Omnipay\Sisow\Message\PurchaseRequest $request */
        $request = $this->gateway->purchase(array(
            'issuer' => '01',
            'amount' => '100.00',
            'currency' => 'EUR',
            'description' => 'desc',
            'language' => 'EN',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));

        $this->assertInstanceOf('Omnipay\Sisow\Message\PurchaseRequest', $request);
        $this->assertSame('01', $request->getIssuer());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('http://localhost/return', $request->getReturnUrl());
        $this->assertSame('http://localhost/notify', $request->getNotifyUrl());
    }

    public function testCompletePurchase()
    {
        /** @var \Omnipay\Sisow\Message\CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase(array(
            'transactionId' => '123456',
        ));

        $this->assertInstanceOf('Omnipay\Sisow\Message\CompletePurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
    }

    public function testAuthorize()
    {
        $request = $this->gateway->authorize(['transactionId' => '123456']);

        self::assertInstanceOf(AuthorizeRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testCompleteAuthorize()
    {
        $request = $this->gateway->completeAuthorize(['transactionId' => '123456']);

        self::assertInstanceOf(CompleteAuthorizeRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testCapture()
    {
        $request = $this->gateway->capture(['transactionId' => '123456']);

        self::assertInstanceOf(CaptureRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testVoid()
    {
        $request = $this->gateway->void(['transactionId' => '123456']);

        self::assertInstanceOf(VoidRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testCredit()
    {
        $request = $this->gateway->credit(['transactionId' => '123456']);

        self::assertInstanceOf(CreditRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(['transactionId' => '123456']);

        self::assertInstanceOf(RefundRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(['transactionId' => '123456']);

        self::assertInstanceOf(FetchTransactionRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }

    public function testFetchIssuerUrl()
    {
        $request = $this->gateway->fetchIssuerUrl(['transactionId' => '123456']);

        self::assertInstanceOf(FetchIssuerUrlRequest::class, $request);
        self::assertSame('123456', $request->getTransactionId());
    }
}
