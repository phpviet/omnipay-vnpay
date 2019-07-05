<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Tests\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VNPay\Message\RefundRequest;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class RefundRequestTest extends TestCase
{
    /**
     * @var RefundRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $this->request = new RefundRequest($client, $request);
    }

    public function testGetData()
    {
        $request = $this->request;
        $request->setVnpCreateDate(1);
        $request->setVnpHashSecret(2);
        $request->setVnpTmnCode(3);
        $request->setVnpIpAddr(4);
        $request->setVnpOrderInfo(5);
        $request->setVnpTxnRef(6);
        $request->setVnpVersion(7);
        $request->setVnpTransDate(8);
        $request->setVnpAmount(9);
        $request->setVnpTransactionType(10);
        $request->setTestMode(true);
        $data = $request->getData();
        $this->assertEquals(12, count($data));
        $this->assertEquals($data['vnp_CreateDate'], $request->getVnpCreateDate());
        $this->assertEquals($data['vnp_TmnCode'], $request->getVnpTmnCode());
        $this->assertEquals($data['vnp_IpAddr'], $request->getVnpIpAddr());
        $this->assertEquals($data['vnp_OrderInfo'], $request->getVnpOrderInfo());
        $this->assertEquals($data['vnp_TxnRef'], $request->getVnpTxnRef());
        $this->assertEquals($data['vnp_TxnRef'], $request->getTransactionId());
        $this->assertEquals($data['vnp_Version'], $request->getVnpVersion());
        $this->assertEquals($data['vnp_TransDate'], $request->getVnpTransDate());
        $this->assertEquals($data['vnp_Amount'], $request->getVnpAmount());
        $this->assertEquals($data['vnp_Amount'], $request->getAmount());
        $this->assertEquals($data['vnp_TransactionType'], $request->getVnpTransactionType());
        $this->assertEquals($data['vnp_TransDate'], $request->getVnpTransDate());
        $this->assertEquals(1, $data['vnp_CreateDate']);
        $this->assertEquals(3, $data['vnp_TmnCode']);
        $this->assertEquals(4, $data['vnp_IpAddr']);
        $this->assertEquals(5, $data['vnp_OrderInfo']);
        $this->assertEquals(6, $data['vnp_TxnRef']);
        $this->assertEquals(7, $data['vnp_Version']);
        $this->assertEquals(8, $data['vnp_TransDate']);
        $this->assertEquals(9, $data['vnp_Amount']);
        $this->assertEquals(10, $data['vnp_TransactionType']);
        $this->assertEquals('refund', $data['vnp_Command']);
        $this->assertTrue(isset($data['vnp_SecureHash']));
        $this->assertFalse(isset($data['vnp_HashSecret']));
    }

}
