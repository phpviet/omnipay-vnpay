<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 * @copyright (c) PHP Viet
 * @license [MIT](http://www.opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\VNPay\Concerns\Parameters;
use Omnipay\VNPay\Concerns\ParametersNormalization;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
abstract class AbstractSignatureRequest extends AbstractRequest
{
    use Parameters;
    use ParametersNormalization;
    use Concerns\RequestEndpoint;
    use Concerns\RequestSignature;

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
        $this->setVnpCreateDate(
            $this->getVnpCreateDate() ?? date('Ymdhis')
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        call_user_func_array(
            [$this, 'validate'],
            $this->getSignatureParameters()
        );

        $parameters = $this->getParameters();
        $parameters['vnp_SecureHash'] = $this->generateSignature(
            $parameters['vnp_SecureHashType'] = $this->getSecureHashType() ?? 'sha256'
        );

        unset($parameters['vnp_HashSecret'], $parameters['testMode']);

        return $parameters;
    }

    /**
     * Trả về mã đơn hàng cần thực thi tác vụ.
     * Đây là phương thức ánh xạ của [[getTransactionId()]].
     *
     * @return null|string
     * @see getTransactionId
     */
    public function getVnpTxnRef(): ?string
    {
        return $this->getTransactionId();
    }

    /**
     * Thiết lập mã đơn hàng cần thực thi tác vụ.
     * Đây là phương thức ánh xạ của [[setTransactionId()]].
     *
     * @param  null|string  $ref
     *
     * @return $this
     * @see setTransactionId
     */
    public function setVnpTxnRef(?string $ref)
    {
        return $this->setTransactionId($ref);
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionId(): ?string
    {
        return $this->getParameter('vnp_TxnRef');
    }

    /**
     * {@inheritdoc}
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('vnp_TxnRef', $value);
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
     * Mặc định sẽ là thời gian hiện tại.
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

    /**
     * Trả về phương thức mã hóa dùng để tạo chữ ký dự liệu (md5, sha256).
     *
     * @return null|string
     * @since 1.0.1
     */
    public function getSecureHashType(): ?string
    {
        return $this->getParameter('vnp_SecureHashType');
    }

    /**
     * Thiết lập phương thức mã hóa dùng để tạo chữ ký dự liệu.
     *
     * @param  null|string  $secureHashType
     *
     * @return $this
     * @since 1.0.1
     */
    public function setSecureHashType(?string $secureHashType)
    {
        return $this->setParameter('vnp_SecureHashType', $secureHashType);
    }
}
