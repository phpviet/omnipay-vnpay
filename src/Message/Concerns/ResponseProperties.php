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
trait ResponseProperties
{
    /**
     * Phương thức hổ trợ tạo các thuộc tính của đối tượng từ dữ liệu gửi về từ VNPay.
     *
     * @param  string  $name
     * @return null|string
     */
    public function __get($name)
    {
        $property = $this->propertyNormalize($name);

        if (isset($this->data[$property])) {
            return $this->data[$property];
        } else {
            trigger_error(sprintf('Undefined property: %s::%s', __CLASS__, '$'.$name), E_USER_NOTICE);

            return;
        }
    }

    /**
     * Phương thức hổ trợ bảo vệ các thuộc tính của đối tượng từ dữ liệu gửi về từ VNPay.
     *
     * @param  string  $name
     * @param  mixed  $value
     * @return null|string
     */
    public function __set($name, $value)
    {
        $property = $this->propertyNormalize($name);

        if (isset($this->data[$property])) {
            trigger_error(sprintf('Undefined property: %s::%s', __CLASS__, '$'.$name), E_USER_NOTICE);
        } else {
            $this->$name = $value;
        }
    }

    /**
     * Phương thức hổ trợ chuyển đổi property `vpcAbc` thành `vpc_Abc`.
     *
     * @param  string  $property
     * @return string
     */
    private function propertyNormalize(string $property): string
    {
        if (0 === strpos($property, 'vnp') && false === strpos($property, '_')) {
            return 'vnp_'.substr($property, 3);
        }

        return $property;
    }
}
