<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Concerns;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
trait Parameters
{
    /**
     * Trả về mã Tmn do VNPay cấp.
     *
     * @return null|string
     */
    public function getVnpVersion(): ?string
    {
        return $this->getParameter('vnp_Version');
    }

    /**
     * Thiết lập mã Tmn.
     *
     * @param  null|string  $code
     * @return $this
     */
    public function setVnpVersion(?string $code)
    {
        return $this->setParameter('vnp_Version', $code);
    }

    /**
     * Trả về mã Tmn do VNPay cấp.
     *
     * @return null|string
     */
    public function getVnpTmnCode(): ?string
    {
        return $this->getParameter('vnp_TmnCode');
    }

    /**
     * Thiết lập mã Tmn.
     *
     * @param  null|string  $code
     * @return $this
     */
    public function setVnpTmnCode(?string $code)
    {
        return $this->setParameter('vnp_TmnCode', $code);
    }

    /**
     * Trả về khóa dùng để tạo chữ ký dữ liệu.
     *
     * @return null|string
     */
    public function getVnpHashSecret(): ?string
    {
        return $this->getParameter('vnp_HashSecret');
    }

    /**
     * Thiết lập khóa dùng để tạo chữ ký dữ liệu.
     *
     * @param  null|string  $secret
     *
     * @return $this
     */
    public function setVnpHashSecret(?string $secret)
    {
        return $this->setParameter('vnp_HashSecret', $secret);
    }
}
