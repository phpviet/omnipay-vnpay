<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message;

use Omnipay\VNPay\Concerns\Parameters;
use Omnipay\VNPay\Concerns\ParametersNormalization;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    use Parameters;
    use ParametersNormalization;

    /**
     * {@inheritdoc}
     */
    public function initialize(array $parameters = [])
    {
        parent::initialize(
            $this->normalizeParameters($parameters)
        );

        $this->setVnpIpAddr(
            $this->getVnpIpAddr() ?? $this->httpRequest->getClientIp()
        );

        return $this;
    }

    /**
     * Trả về mã đơn hàng cần thực thi tác vụ.
     *
     * @return null|string
     */
    public function getVnpTxnRef(): ?string
    {
        return $this->getParameter('vnp_TxnRef');
    }

    /**
     * Thiết lập mã đơn hàng cần thực thi tác vụ.
     *
     * @param  null|string  $ref
     *
     * @return $this
     */
    public function setVnpTxnRef(?string $ref)
    {
        return $this->setParameter('vnp_TxnRef', $ref);
    }

    /**
     * Trả về thông tin đơn hàng hay lý do truy vấn đến VNPay.
     *
     * @return null|string
     */
    public function getVnpOrderInfo(): ?string
    {
        return $this->getParameter('vnp_OrderInfo');
    }

    /**
     * Thiết lập thông tin đơn hàng hay lý do truy vấn đến VNPay.
     *
     * @param  null|string  $info
     * @return $this
     */
    public function setVnpOrderInfo(?string $info)
    {
        return $this->setParameter('vnp_OrderInfo', $info);
    }

    /**
     * Trả về thời gian khởi tạo truy vấn đến VNPay.
     *
     * @return null|string
     * @see getVnpReturnUrl
     */
    public function getVnpCreateDate(): ?string
    {
        return $this->getParameter('vnp_CreateDate');
    }

    /**
     * Thiết lập thời gian khởi tạo truy vấn đến VNPay.
     *
     * @param  null|string  $date
     * @return $this
     * @see setReturnUrl
     */
    public function setVnpCreateDate(?string $date)
    {
        return $this->setParameter('vnp_CreateDate', $date);
    }

    /**
     * Trả về ip của khách dùng để thanh toán.
     * Đây là phương thức ánh xạ của [[getClientIp()]].
     *
     * @return null|string
     * @see getClientIp
     */
    public function getVnpIpAddr(): ?string
    {
        return $this->getClientIp();
    }

    /**
     * Thiết lập ip của khách dùng để thanh toán.
     * Đây là phương thức ánh xạ của [[setClientIp()]].
     * Mặc định nếu không thiết lập sẽ là IP của khách.
     *
     * @param  null|string  $ip
     * @return $this
     * @see setClientIp
     */
    public function setVnpIpAddr(?string $ip)
    {
        return $this->setClientIp($ip);
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp(): ?string
    {
        return $this->getParameter('vnp_IpAddr');
    }

    /**
     * {@inheritdoc}
     */
    public function setClientIp($value)
    {
        return $this->setParameter('vnp_IpAddr', $value);
    }
}
