<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Tests;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\VNPay\Message\PurchaseResponse;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\VNPay\Gateway
     */
    protected $gateway;

    protected function setUp()
    {
        $this->gateway = Omnipay::create('VNPay', $this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setVnpTmnCode('COCOSIN');
        $this->gateway->setVnpHashSecret('RAOEXHYVSDDIIENYWSLDIIZTANXUXZFJ');
        $this->gateway->setTestMode(true);
    }

    public function testPurchaseSuccess()
    {
        $response = $this->gateway->purchase([
            'vnp_TxnRef' => time(),
            'vnp_OrderType' => 100000,
            'vnp_OrderInfo' => time(),
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_Amount' => 1000000,
            'vnp_ReturnUrl' => 'https://github.com/phpviet',
        ])->send();

        $this->assertInstanceOf(RedirectResponseInterface::class, $response);
        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectUrl());
    }

    public function testPurchaseFailure()
    {
        $this->expectException(InvalidRequestException::class);
        $this->gateway->purchase([
            'vnp_TxnRef' => time(),
            'vnp_OrderType' => 100000,
            'OrderInfo' => time(),
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_Amount' => 1000000,
            'vnp_ReturnUrl' => 'https://github.com/phpviet',
        ])->send();
    }

    /**
     * @testWith    ["completePurchase"]
     *              ["notification"]
     */
    public function testIncomingSuccess(string $requestMethod)
    {
        $this->getHttpRequest()->query->replace([
            'vnp_Amount' => 1000000,
            'vnp_BankCode' => 'NCB',
            'vnp_BankTranNo' => 20170829152730,
            'vnp_CardType' => 'ATM',
            'vnp_OrderInfo' => 'Thanh+toan+don+hang+thoi+gian%3A+2017-08-29+15%3A27%3A02',
            'vnp_PayDate' => 20170829153052,
            'vnp_ResponseCode' => '00',
            'vnp_TmnCode' => '2QXUI4J4',
            'vnp_TransactionNo' => 12996460,
            'vnp_TxnRef' => 23597,
            'vnp_SecureHash' => '32c2be7c9a4282ca13ce4a5e443902fe',
            'vnp_SecureHashType' => 'md5',
        ]);
        $response = call_user_func([$this->gateway, $requestMethod])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals(12996460, $response->getTransactionReference());
        $this->assertEquals(23597, $response->getTransactionId());
        $this->assertEquals('ATM', $response->vnp_CardType);
        $this->assertEquals(20170829153052, $response->vnpPayDate);
    }

    /**
     * @testWith    ["completePurchase"]
     *              ["notification"]
     */
    public function testIncomingFailure(string $requestMethod)
    {
        $this->getHttpRequest()->query->replace([
            'vnp_Amount' => 1000000,
            'vnp_ResponseCode' => '00',
            'vnp_SecureHash' => '32c2be7c9a4282ca13ce4a5e443902fe',
        ]);

        $this->expectException(InvalidResponseException::class);

        call_user_func([$this->gateway, $requestMethod])->send();
    }

    public function testQueryTransactionSuccess()
    {
        $this->setMockHttpResponse('QueryTransactionSuccess.txt');
        $response = $this->gateway->queryTransaction([
            'vnp_TransDate' => 20190705151126,
            'vnp_TxnRef' => 1562314234,
            'vnp_OrderInfo' => time(),
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_TransactionNo' => 496558,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals(1562314234, $response->getTransactionId());
        $this->assertEquals(496558, $response->getTransactionReference());
    }

    public function testQueryTransactionFailure()
    {
        $this->setMockHttpResponse('QueryTransactionFailure.txt');
        $response = $this->gateway->queryTransaction([
            'vnp_TransDate' => 20190705151126,
            'vnp_TxnRef' => 15623142234,
            'vnp_OrderInfo' => time(),
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_TransactionNo' => 4961111558,
        ])->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(91, $response->getCode());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->gateway->refund([
            'vnp_Amount' => 10000,
            'vnp_TransactionType' => '03',
            'vnp_TransDate' => 20190705151126,
            'vnp_TxnRef' => 32321,
            'vnp_OrderInfo' => time(),
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_TransactionNo' => 496558,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(496558, $response->getTransactionReference());
    }

    public function testRefundFailure()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->gateway->refund([
            'vnp_TxnRef' => 23597,
            'vnp_Amount' => 10000,
            'vnp_TransactionType' => '03',
            'vnp_OrderInfo' => time(),
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_TransDate' => 20190705151126,
        ])->send();

        $this->assertEquals(99, $response->getCode());
        $this->assertFalse($response->isSuccessful());
    }

    public function testDefaultParametersHaveMatchingMethods()
    {
        $settings = $this->gateway->getDefaultParameters();
        foreach ($settings as $key => $default) {
            $key = str_replace('_', '', $key);
            $getter = 'get'.$key;
            $setter = 'set'.$key;
            $value = uniqid();

            $this->assertTrue(method_exists($this->gateway, $getter), "Gateway must implement $getter()");
            $this->assertTrue(method_exists($this->gateway, $setter), "Gateway must implement $setter()");
            $this->assertSame($this->gateway, $this->gateway->$setter($value));
            $this->assertSame($value, $this->gateway->$getter());
        }
    }

    public function testPurchaseParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            $key = str_replace('_', '', $key);
            $getter = 'get'.$key;
            $setter = 'set'.$key;
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->purchase();
            $this->assertSame($value, $request->$getter());
        }
    }

    public function testRefundParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            $key = str_replace('_', '', $key);
            $getter = 'get'.$key;
            $setter = 'set'.$key;
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->refund();
            $this->assertSame($value, $request->$getter());
        }
    }

    public function testCompletePurchaseParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            $key = str_replace('_', '', $key);
            $getter = 'get'.$key;
            $setter = 'set'.$key;
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->completePurchase();
            $this->assertSame($value, $request->$getter());
        }
    }

    public function testQueryTransactionParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            $key = str_replace('_', '', $key);
            $getter = 'get'.$key;
            $setter = 'set'.$key;
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->queryTransaction();
            $this->assertSame($value, $request->$getter());
        }
    }

    public function testNotificationParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            $key = str_replace('_', '', $key);
            $getter = 'get'.$key;
            $setter = 'set'.$key;
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->notification();
            $this->assertSame($value, $request->$getter());
        }
    }
}
