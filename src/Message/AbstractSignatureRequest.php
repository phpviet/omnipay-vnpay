<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 * @copyright (c) PHP Viet
 * @license [MIT](http://www.opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
abstract class AbstractSignatureRequest extends AbstractRequest
{
    use Concerns\RequestEndpoint;
    use Concerns\RequestSignature;

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        call_user_func_array(
            [$this, 'validate'],
            $this->getSignatureParameters()
        );;

        $parameters = $this->getParameters();
        $parameters['vnp_SecureHash'] = $this->generateSignature(
            $parameters['vnp_SecureHashType'] = 'SHA256'
        );

        unset($parameters['vnp_HashSecret'], $parameters['testMode']);

        return $parameters;
    }
}
