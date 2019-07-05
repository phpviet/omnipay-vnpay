<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Tests\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VNPay\Message\PurchaseResponse;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class PurchaseResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new PurchaseResponse($this->getMockRequest(), [
            'example' => 'value',
            'foo' => 'bar',
        ], 'john doe');

        $this->assertEquals(['example' => 'value', 'foo' => 'bar'], $response->getData());
        $this->assertEquals('john doe', $response->getRedirectUrl());
    }

    public function testPurchase()
    {
        $response = new PurchaseResponse($this->getMockRequest(), [
            'vnp_TxnRef' => 123,
        ], 'john doe');
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('123', $response->getTransactionId());
        $this->assertEquals('GET', $response->getRedirectMethod());
        $this->assertEquals('123', $response->vnp_TxnRef);
        $this->assertEquals('123', $response->vnpTxnRef);
    }
}
