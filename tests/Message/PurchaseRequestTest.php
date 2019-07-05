<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Tests\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VNPay\Message\PurchaseRequest;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $this->request = new PurchaseRequest($client, $request);
    }

    public function testGetData()
    {
        $request = $this->request;
        $request->setVnpCreateDate(1);
        $request->setVnpHashSecret(2);
        $request->setVnpTmnCode(3);
        $request->setVnpIpAddr(4);
        $request->setVnpCurrCode(5);
        $request->setVnpLocale(6);
        $request->setVnpAmount(7);
        $request->setVnpOrderInfo(8);
        $request->setVnpOrderType(9);
        $request->setVnpReturnUrl(10);
        $request->setVnpTxnRef(11);
        $request->setVnpVersion(12);
        $request->setTestMode(true);
        $data = $request->getData();
        $this->assertEquals(14, count($data));
        $this->assertEquals($data['vnp_CreateDate'], $request->getVnpCreateDate());
        $this->assertEquals($data['vnp_TmnCode'], $request->getVnpTmnCode());
        $this->assertEquals($data['vnp_IpAddr'], $request->getVnpIpAddr());
        $this->assertEquals($data['vnp_CurrCode'], $request->getVnpCurrCode());
        $this->assertEquals($data['vnp_Locale'], $request->getVnpLocale());
        $this->assertEquals($data['vnp_Amount'], $request->getVnpAmount());
        $this->assertEquals($data['vnp_OrderInfo'], $request->getVnpOrderInfo());
        $this->assertEquals($data['vnp_OrderType'], $request->getVnpOrderType());
        $this->assertEquals($data['vnp_ReturnUrl'], $request->getVnpReturnUrl());
        $this->assertEquals($data['vnp_TxnRef'], $request->getVnpTxnRef());
        $this->assertEquals($data['vnp_Version'], $request->getVnpVersion());
        $this->assertEquals($data['vnp_Command'], 'pay');
        $this->assertEquals(1, $data['vnp_CreateDate']);
        $this->assertEquals(3, $data['vnp_TmnCode']);
        $this->assertEquals(4, $data['vnp_IpAddr']);
        $this->assertEquals(5, $data['vnp_CurrCode']);
        $this->assertEquals(6, $data['vnp_Locale']);
        $this->assertEquals(7, $data['vnp_Amount']);
        $this->assertEquals(8, $data['vnp_OrderInfo']);
        $this->assertEquals(9, $data['vnp_OrderType']);
        $this->assertEquals(10, $data['vnp_ReturnUrl']);
        $this->assertEquals(11, $data['vnp_TxnRef']);
        $this->assertEquals(12, $data['vnp_Version']);
        $this->assertTrue(isset($data['vnp_SecureHash']));
        $this->assertFalse(isset($data['vnp_HashSecret']));
    }

}
