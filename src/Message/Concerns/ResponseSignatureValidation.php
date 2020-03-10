<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 * @copyright (c) PHP Viet
 * @license [MIT](http://www.opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message\Concerns;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\VNPay\Support\Signature;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
trait ResponseSignatureValidation
{
    /**
     * Kiểm tra tính hợp lệ của dữ liệu do VNPay phản hồi.
     *
     * @throws InvalidResponseException
     */
    protected function validateSignature(): void
    {
        $data = $this->getData();

        if (! isset($data['vnp_SecureHash'])) {
            throw new InvalidResponseException('Response from VNPay is invalid!');
        }

        $dataSignature = array_filter($this->getData(), function ($parameter) {
            return 0 === strpos($parameter, 'vnp_')
                && 'vnp_SecureHash' !== $parameter
                && 'vnp_SecureHashType' !== $parameter;
        }, ARRAY_FILTER_USE_KEY);

        $signature = new Signature(
            $this->getRequest()->getVnpHashSecret(),
            $data['vnp_SecureHashType'] ?? 'md5'
        );

        if (! $signature->validate($dataSignature, $data['vnp_SecureHash'])) {
            throw new InvalidResponseException(sprintf('Data signature response from VNPay is invalid!'));
        }
    }
}
