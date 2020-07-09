<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class PurchaseRequest extends AbstractSignatureRequest
{
    /**
     * {@inheritdoc}
     */
    protected $productionEndpoint = 'https://pay.vnpay.vn/vpcpay.html';

    /**
     * {@inheritdoc}
     */
    protected $testEndpoint = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

    /**
     * {@inheritdoc}
     */
    public function initialize(array $parameters = [])
    {
        parent::initialize($parameters);

        $this->setParameter('vnp_Command', 'pay');
        $this->setVnpLocale(
            $this->getVnpLocale() ?? 'vn'
        );
        $this->setVnpCurrCode(
            $this->getVnpCurrCode() ?? 'VND'
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): PurchaseResponse
    {
        $query = http_build_query($data);
        $redirectUrl = $this->getEndpoint().'?'.$query;

        return $this->response = new PurchaseResponse($this, $data, $redirectUrl);
    }

    /**
     * Trả về vùng ngôn ngữ hiển thị trên VNPay khi khách thanh toán.
     *
     * @return null|string
     */
    public function getVnpLocale(): ?string
    {
        return $this->getParameter('vnp_Locale');
    }

    /**
     * Thiết lập  vùng ngôn ngữ hiển thị trên VNPay khi khách thanh toán.
     * Mặc định nếu không thiết lập sẽ là `vn`.
     *
     * @param  null|string  $locale
     *
     * @return $this
     */
    public function setVnpLocale(?string $locale)
    {
        return $this->setParameter('vnp_Locale', $locale);
    }

    /**
     * Trả về mã tiền tệ dùng để thanh toán.
     * Đây là phương thức ánh xạ của [[getCurrency()]].
     *
     * @return null|string
     * @see getCurrency
     */
    public function getVnpCurrCode(): ?string
    {
        return $this->getCurrency();
    }

    /**
     * Thiết lập mã tiền tệ dùng để thanh toán.
     * Đây là phương thức ánh xạ của [[setCurrency()]].
     *
     * @param  null|string  $code
     * @return $this
     * @see setCurrency
     */
    public function setVnpCurrCode(?string $code)
    {
        return $this->setCurrency($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency(): ?string
    {
        return $this->getParameter('vnp_CurrCode');
    }

    /**
     * {@inheritdoc}
     * Mặc định nếu không thiết lập sẽ là `VND`.
     */
    public function setCurrency($value)
    {
        return $this->setParameter('vnp_CurrCode', $value);
    }

    /**
     * Trả về mã ngân hàng dùng để thanh toán.
     *
     * @return null|string
     */
    public function getVnpBankCode(): ?string
    {
        return $this->getParameter('vnp_BankCode');
    }

    /**
     * Thiết lập mã ngân hàng dùng để thanh toán.
     *
     * @param  null|string  $code
     * @return $this
     */
    public function setVnpBankCode(?string $code)
    {
        return $this->setParameter('vnp_BankCode', $code);
    }

    /**
     * Trả về nhóm loại đơn hàng.
     *
     * @return null|string
     */
    public function getVnpOrderType(): ?string
    {
        return $this->getParameter('vnp_OrderType');
    }

    /**
     * Thiết lập nhóm loại đơn hàng.
     *
     * @param  null|string  $type
     * @return $this
     */
    public function setVnpOrderType(?string $type)
    {
        return $this->setParameter('vnp_OrderType', $type);
    }

    /**
     * Trả về số tiền của đơn hàng.
     * Đây là phương thức ánh xạ của [[getAmount()]].
     *
     * @return null|string
     * @see getAmount
     */
    public function getVnpAmount(): ?string
    {
        return $this->getAmount();
    }

    /**
     * Thiết lập số tiền cần thanh toán.
     * Đây là phương thức ánh xạ của [[setAmount()]].
     *
     * @param  null|string  $number
     * @return $this
     * @see setAmount
     */
    public function setVnpAmount(?string $number)
    {
        return $this->setAmount($number);
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): ?string
    {
        return $this->getParameter('vnp_Amount');
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($value)
    {
        return $this->setParameter('vnp_Amount', $value);
    }

    /**
     * Trả về url sẽ dẫn khách về sau khi thanh toán xong.
     * Đây là phương thức ánh xạ của [[getVnpReturnUrl()]].
     *
     * @return null|string
     * @see getVnpReturnUrl
     */
    public function getVnpReturnUrl(): ?string
    {
        return $this->getReturnUrl();
    }

    /**
     * Thiết lập url dẫn khách về sau khi thanh toán xong.
     * Đây là phương thức ánh xạ của [[setReturnUrl()]].
     *
     * @param  null|string  $url
     * @return $this
     * @see setReturnUrl
     */
    public function setVnpReturnUrl(?string $url)
    {
        return $this->setReturnUrl($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnUrl(): ?string
    {
        return $this->getParameter('vnp_ReturnUrl');
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('vnp_ReturnUrl', $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSignatureParameters(): array
    {
        $parameters = [
            'vnp_CreateDate', 'vnp_IpAddr', 'vnp_ReturnUrl', 'vnp_Amount', 'vnp_OrderType', 'vnp_OrderInfo',
            'vnp_TxnRef', 'vnp_CurrCode', 'vnp_Locale', 'vnp_TmnCode', 'vnp_Command', 'vnp_Version',
        ];

        if ($this->getVnpBankCode()) {
            $parameters[] = 'vnp_BankCode';
        }

        return $parameters;
    }
}
