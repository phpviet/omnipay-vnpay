<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Tests\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VNPay\Message\SignatureResponse;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class SignatureResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new SignatureResponse($this->getMockRequest(), [
            'example' => 'value',
            'foo' => 'bar',
        ]);

        $this->assertEquals(['example' => 'value', 'foo' => 'bar'], $response->getData());
    }

    public function testIncoming()
    {
        $request = $this->getMockRequest();
        $request->shouldReceive('getVnpHashSecret')->once()->andReturn('RAOEXHYVSDDIIENYWSLDIIZTANXUXZFJ');
        $response = new SignatureResponse($request, [
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

        $this->assertFalse($response->isPending());
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('23597', $response->getTransactionId());
        $this->assertEquals('12996460', $response->getTransactionReference());
        $this->assertEquals('00', $response->getCode());
    }

}
