<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 * @copyright (c) PHP Viet
 * @license [MIT](http://www.opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message\Concerns;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
trait RequestEndpoint
{
    /**
     * Đường dẫn giao tiếp với VNPay ở môi trường production.
     *
     * @var string
     */
    protected $productionEndpoint;

    /**
     * Đường dẫn giao tiếp với VNPay ở môi trường test.
     *
     * @var string
     */
    protected $testEndpoint;

    /**
     * Trả về url kết nối VNPay.
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->productionEndpoint;
    }
}
