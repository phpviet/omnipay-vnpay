<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 * @copyright (c) PHP Viet
 * @license [MIT](http://www.opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Support;

use InvalidArgumentException;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Signature
{
    /**
     * Khóa bí mật dùng để tạo và kiểm tra chữ ký dữ liệu.
     *
     * @var string
     */
    protected $hashSecret;

    /**
     * Loại thuật toán mã hóa sẽ sử dụng.
     *
     * @var string
     */
    protected $hashType;

    /**
     * Khởi tạo đối tượng DataSignature.
     *
     * @param  string  $hashSecret
     * @param  string  $hashType
     * @throws InvalidArgumentException
     */
    public function __construct(string $hashSecret, string $hashType = 'sha256')
    {
        if (! $this->isSupportHashType($hashType)) {
            throw new InvalidArgumentException(sprintf('Hash type: `%s` is not supported by VNPay', $hashType));
        }

        $this->hashType = $hashType;
        $this->hashSecret = $hashSecret;
    }

    /**
     * Trả về chữ ký dữ liệu của dữ liệu truyền vào.
     *
     * @param  array  $data
     * @return string
     */
    public function generate(array $data): string
    {
        ksort($data);
        $dataSign = $this->hashSecret.urldecode(http_build_query($data));

        return hash($this->hashType, $dataSign);
    }

    /**
     * Kiểm tra tính hợp lệ của chữ ký dữ liệu so với dữ liệu truyền vào.
     *
     * @param  array  $data
     * @param  string  $expect
     * @return bool
     */
    public function validate(array $data, string $expect): bool
    {
        $actual = $this->generate($data);

        return 0 === strcasecmp($expect, $actual);
    }

    /**
     * Phương thức cho biết loại mã hóa truyền vào có được VNPay hổ trợ hay không.
     *
     * @param  string  $type
     * @return bool
     */
    protected function isSupportHashType(string $type): bool
    {
        return 0 === strcasecmp($type, 'md5') || 0 === strcasecmp($type, 'sha256');
    }
}
