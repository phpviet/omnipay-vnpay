<p align="center">
    <a href="https://vnpay.vn" target="_blank">
        <img src="https://raw.githubusercontent.com/phpviet/omnipay-vnpay/master/resources/logo.png">
    </a>
    <h1 align="center">Omnipay: VNPay</h1>
    <br>
    <p align="center">
    <a href="https://packagist.org/packages/phpviet/omnipay-vnpay"><img src="https://img.shields.io/packagist/v/phpviet/omnipay-vnpay.svg?style=flat-square" alt="Latest version"></a>
    <a href="https://travis-ci.org/phpviet/omnipay-vnpay"><img src="https://img.shields.io/travis/phpviet/omnipay-vnpay/master.svg?style=flat-square" alt="Build status"></a>
    <a href="https://scrutinizer-ci.com/g/phpviet/omnipay-vnpay"><img src="https://img.shields.io/scrutinizer/g/phpviet/omnipay-vnpay.svg?style=flat-square" alt="Quantity score"></a>
    <a href="https://styleci.io/repos/189053790"><img src="https://styleci.io/repos/189053790/shield?branch=master" alt="StyleCI"></a>
    <a href="https://packagist.org/packages/phpviet/omnipay-vnpay"><img src="https://img.shields.io/packagist/dt/phpviet/omnipay-vnpay.svg?style=flat-square" alt="Total download"></a>
    <a href="https://packagist.org/packages/phpviet/omnipay-vnpay"><img src="https://img.shields.io/packagist/l/phpviet/omnipay-vnpay.svg?style=flat-square" alt="License"></a>
    </p>
</p>

## Thông tin

Thư viện hổ trợ tích cổng thanh toán VNPay phát triển trên nền tảng [Omnipay League](https://github.com/thephpleague/omnipay).

Để nắm sơ lược về khái niệm và cách sử dụng các **Omnipay** gateways bạn hãy truy cập vào [đây](https://omnipay.thephpleague.com/) 
để kham khảo.

## Cài đặt

Cài đặt Omnipay VNPay thông qua [Composer](https://getcomposer.org):

```bash
composer require phpviet/omnipay-vnpay
```
## Cách sử dụng

### Tích hợp sẵn trên các framework phổ biến hiện tại

- [`Laravel`](https://github.com/phpviet/laravel-omnipay)
- [`Symfony`](https://github.com/phpviet/symfony-omnipay)
- [`Yii`](https://github.com/phpviet/yii-omnipay)

hoặc nếu bạn muốn sử dụng không dựa trên framework thì tiếp tục xem tiếp.

### Khởi tạo gateway:

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('VNPay');
$gateway->initialize([
    'vnp_TmnCode' => 'Do VNPay cấp',
    'vnp_HashSecret' => 'Do VNPay cấp',
]);
```

Gateway khởi tạo ở trên dùng để tạo các yêu cầu xử lý đến VNPay hoặc dùng để nhận yêu cầu do VNPay gửi đến.

### Tạo yêu cầu thanh toán:

```php
$response = $gateway->purchase([
    'vnp_TxnRef' => time(),
    'vnp_OrderType' => 100000,
    'vnp_OrderInfo' => time(),
    'vnp_IpAddr' => '127.0.0.1',
    'vnp_Amount' => 1000000,
    'vnp_ReturnUrl' => 'https://github.com/phpviet',
])->send();

if ($response->isRedirect()) {
    $redirectUrl = $response->getRedirectUrl();
    
    // TODO: chuyển khách sang trang VNPay để thanh toán
}
```

Kham khảo thêm các tham trị khi tạo yêu cầu và VNPay trả về tại [đây](https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop/#t%E1%BA%A1o-url-thanh-to%C3%A1n).


### Kiểm tra thông tin `vnp_ReturnUrl` khi khách được VNPay redirect về:

```php
$response = $gateway->completePurchase()->send();

if ($response->isSuccessful()) {
    // TODO: xử lý kết quả và hiển thị.
    print $response->vnp_Amount;
    print $response->vnp_TxnRef;
    
    var_dump($response->getData()); // toàn bộ data do VNPay gửi sang.
    
} else {

    print $response->getMessage();
}
```

Kham khảo thêm các tham trị khi VNPay trả về tại [đây](https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop/#code-returnurl).

### Kiểm tra thông tin `IPN` do VNPay gửi sang:

```php
$response = $gateway->notification()->send();

if ($response->isSuccessful()) {
    // TODO: xử lý kết quả.
    print $response->vnp_Amount;
    print $response->vnp_TxnRef;
    
    var_dump($response->getData()); // toàn bộ data do VNPay gửi sang.
    
} else {

    print $response->getMessage();
}
```

Kham khảo thêm các tham trị khi VNPay gửi sang tại [đây](https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop/#code-ipn-url).

### Kiểm tra trạng thái giao dịch:

```php
$response = $gateway->queryTransaction([
    'vnp_TransDate' => 20190705151126,
    'vnp_TxnRef' => 1562314234,
    'vnp_OrderInfo' => time(),
    'vnp_IpAddr' => '127.0.0.1',
    'vnp_TransactionNo' => 496558,
])->send();

if ($response->isSuccessful()) {
    // TODO: xử lý kết quả và hiển thị.
    print $response->getTransactionId();
    print $response->getTransactionReference();
    
    var_dump($response->getData()); // toàn bộ data do VNPay gửi về.
    
} else {

    print $response->getMessage();
}
```

Kham khảo thêm các tham trị khi tạo yêu cầu và VNPay trả về tại [đây](https://goo.gl/FHdM5B).

### Yêu cầu hoàn tiền:

```php
$response = $gateway->refund([
    'vnp_Amount' => 10000,
    'vnp_TransactionType' => '03',
    'vnp_TransDate' => 20190705151126,
    'vnp_TxnRef' => 32321,
    'vnp_OrderInfo' => time(),
    'vnp_IpAddr' => '127.0.0.1',
    'vnp_TransactionNo' => 496558,
])->send();

if ($response->isSuccessful()) {
    // TODO: xử lý kết quả và hiển thị.
    print $response->getTransactionId();
    print $response->getTransactionReference();
    
    var_dump($response->getData()); // toàn bộ data do VNPay gửi về.
    
} else {

    print $response->getMessage();
}
```

Kham khảo thêm các tham trị khi tạo yêu cầu và VNPay trả về tại [đây](https://goo.gl/FHdM5B).

## Dành cho nhà phát triển

Nếu như bạn cảm thấy thư viện chúng tôi còn thiếu sót hoặc sai sót và bạn muốn đóng góp để phát triển chung, 
chúng tôi rất hoan nghênh! Hãy tạo các `issue` để đóng góp ý tưởng cho phiên bản kế tiếp hoặc tạo `PR` 
để đóng góp phần thiếu sót hoặc sai sót. Riêng đối với các lỗi liên quan đến bảo mật thì phiền bạn gửi email đến
vuongxuongminh@gmail.com thay vì tạo issue. Cảm ơn!
