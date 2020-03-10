<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\VNPay\Message\IncomingRequest;
use Omnipay\VNPay\Message\PurchaseRequest;
use Omnipay\VNPay\Message\QueryTransactionRequest;
use Omnipay\VNPay\Message\RefundRequest;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Gateway extends AbstractGateway
{
    use Concerns\Parameters;
    use Concerns\ParametersNormalization;

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'VNPay';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $parameters = [])
    {
        return parent::initialize(
            $this->normalizeParameters($parameters)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'vnp_Version' => '2.0.0',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function purchase(array $options = []): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * {@inheritdoc}
     * @return \Omnipay\Common\Message\AbstractRequest|IncomingRequest
     */
    public function completePurchase(array $options = []): IncomingRequest
    {
        return $this->createRequest(IncomingRequest::class, $options);
    }

    /**
     * Tạo yêu cầu xác minh IPN gửi từ VNPay.
     *
     * @param  array  $options
     * @return \Omnipay\Common\Message\AbstractRequest|IncomingRequest
     */
    public function notification(array $options = []): IncomingRequest
    {
        return $this->createRequest(IncomingRequest::class, $options);
    }

    /**
     * Tạo yêu cầu truy vấn thông tin giao dịch đến VNPay.
     *
     * @param  array  $options
     * @return \Omnipay\Common\Message\AbstractRequest|QueryTransactionRequest
     */
    public function queryTransaction(array $options = []): QueryTransactionRequest
    {
        return $this->createRequest(QueryTransactionRequest::class, $options);
    }

    /**
     * {@inheritdoc}
     * @return \Omnipay\Common\Message\AbstractRequest|RefundRequest
     */
    public function refund(array $options = []): RefundRequest
    {
        return $this->createRequest(RefundRequest::class, $options);
    }
}
