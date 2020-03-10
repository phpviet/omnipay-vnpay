<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\VNPay\Concerns\Parameters;
use Omnipay\VNPay\Concerns\ParametersNormalization;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class IncomingRequest extends AbstractRequest
{
    use Parameters;
    use ParametersNormalization;

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        call_user_func_array(
            [$this, 'validate'],
            array_keys($parameters = $this->getIncomingParameters())
        );

        return $parameters;
    }

    /**
     * {@inheritdoc}
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    public function sendData($data): SignatureResponse
    {
        return $this->response = new SignatureResponse($this, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $parameters = [])
    {
        parent::initialize(
            $this->normalizeParameters($parameters)
        );

        foreach ($this->getIncomingParameters() as $parameter => $value) {
            $this->setParameter($parameter, $value);
        }

        return $this;
    }

    /**
     * Trả về danh sách parameters từ VNPay gửi sang.
     *
     * @return array
     */
    protected function getIncomingParameters(): array
    {
        return $this->httpRequest->query->all();
    }
}
