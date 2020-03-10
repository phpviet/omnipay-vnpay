<?php
/**
 * @link https://github.com/phpviet/omnipay-vnpay
 *
 * @copyright (c) PHP Viet
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace Omnipay\VNPay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class PurchaseResponse extends Response implements RedirectResponseInterface
{
    /**
     * Đường dẫn dẫn khách đến hệ thống VNPay để thanh toán.
     *
     * @var string
     */
    private $redirectUrl;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestInterface $request, array $data, string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;

        parent::__construct($request, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
