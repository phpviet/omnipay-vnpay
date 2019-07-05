<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Tests\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VNPay\Message\IncomingRequest;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class IncomingRequestTest extends TestCase
{
    /**
     * @var IncomingRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $request->query->replace([
            'vnp_Amount' => 1,
            'vnp_BankCode' => 2,
            'vnp_BankTranNo' => 3,
            'vnp_CardType' => 4,
            'vnp_OrderInfo' => 5,
            'vnp_PayDate' => 6,
            'vnp_ResponseCode' => 7,
            'vnp_TmnCode' => 8,
            'vnp_TransactionNo' => 9,
            'vnp_TxnRef' => 10,
            'vnp_SecureHash' => 11,
            'vnp_SecureHashType' => 12,
        ]);
        $this->request = new IncomingRequest($client, $request);
    }

    public function testIncoming()
    {
        $data = $this->request->getData();
        $this->assertEquals(12, count($data));
        $this->assertEquals(1, $data['vnp_Amount']);
        $this->assertEquals(2, $data['vnp_BankCode']);
        $this->assertEquals(3, $data['vnp_BankTranNo']);
        $this->assertEquals(4, $data['vnp_CardType']);
        $this->assertEquals(5, $data['vnp_OrderInfo']);
        $this->assertEquals(6, $data['vnp_PayDate']);
        $this->assertEquals(7, $data['vnp_ResponseCode']);
        $this->assertEquals(8, $data['vnp_TmnCode']);
        $this->assertEquals(9, $data['vnp_TransactionNo']);
        $this->assertEquals(10, $data['vnp_TxnRef']);
        $this->assertEquals(11, $data['vnp_SecureHash']);
        $this->assertEquals(12, $data['vnp_SecureHashType']);
    }
}