<?php

namespace App\Enums\ViewPaths\Admin;

enum RemoteConnections
{
    const TAWSEEL = [
        'URL' => 'https://demo-apitawseel.naql.sa',
        'regions' => '/api/Lookup/regions-list',
        'cities' => '/api/Lookup/cities-list',
        'car-types' => '/api/Lookup/car-types-list',
        'countryList' => '/api/Lookup/countries-list',
        'payments' => '/api/Lookup/payment-methods-list',
        'identities' => '/api/Lookup/identity-types-list',
        'authorityList' => '/api/Lookup/authorities-list',
        'categoriesList' => '/api/Lookup/categories-list',
        'cancellation-reasons-list' => '/api/Lookup/cancellation-reasons-list',
        'CreateDriver' => '/api/Driver/create',
        'editDriver' => '/api/Driver/edit',
        'getDriver' => '/api/Driver/get',
        'deActivateDriver' => '/api/Driver/deActivate',
        'CreateOrder' => '/api/Order/create',
        'bulk' => '/api/Order/uploadBulk',
        'bulkFile' => '/api/Order/downloadBulk',
        'acceptOrder' => '/api/Order/accept',
        'rejectOrder' => '/api/Order/reject',
        'executeOrder' => '/api/Order/execute',
        'editOrderDeliveryAddress' => '/api/Order/edit-order-delivery-address',
        'assignDriverToOrder' => '/api/Order/assign-driver-to-order',
        'cancelOrder' => '/api/Order/cancel',
        'getOrder' => '/api/Order/get',
        'getContactInfo' => '/api/app/contact-info/get',
        'ContactInfo' => '/api/app/contact-info',
        'credential' => [
            "companyName" => "Sayer",
            "password" => "ZJm55D4RGyuqXytqfxtYf7LbO9mTaesUEaO5DnAcqIoTYgKGqgwFSfr5MZeN1l22"
        ],
    ];

    const STC = [
        'paymentOrderURL' => 'https://sandbox.b2b.stcpay.com.sa/B2B.MerchantTransactionsWebApi/MerchantTransactions/v3/PaymentOrderPay',
        'paymentOrderInqueryURL' => 'https://sandbox.b2b.stcpay.com.sa/B2B.MerchantTransactionsWebApi/MerchantTransactions/v3/paymentOrderInquiry',
        'DirectPaymentAuthorize' => 'https://sandbox.b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentAuthorize',
        'DirectPaymentConfirm' => 'https://sandbox.b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentConfirm',
        'PaymentInquiry' => 'https://sandbox.b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/PaymentInquiry',

        'credential' => [
            "companyName" => "Sayer",
            "password" => "ZJm55D4RGyuqXytqfxtYf7LbO9mTaesUEaO5DnAcqIoTYgKGqgwFSfr5MZeN1l22"
        ],
        'headers' => [
            'X-ClientCode' => '72006166322',
        ],
    ];
    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.city.index'
    ];

    const UPDATE = [
        URI => 'edit',
        VIEW => 'admin-views.city.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const LATEST_MODULE_SETUP = [
        URI => 'module-setup',
        VIEW => 'admin-views.city.module-setup'
    ];

    const MODULE_SETUP = [
        URI => 'module-setup',
        VIEW => 'admin-views.city.module-setup'
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const DIGITAL_PAYMENT = [
        URI => 'digital-payment',
        VIEW => ''
    ];

    const CASH_ON_DELIVERY = [
        URI => 'cash-on-delivery',
        VIEW => ''
    ];

    const OFFLINE_PAYMENT = [
        URI => 'offline-payment',
        VIEW => ''
    ];

    const INSTRUCTION = [
        URI => 'instruction',
        VIEW => ''
    ];

    const CITY_FILTER = [
        URI => 'city-filter',
        VIEW => ''
    ];

    const MODULE_UPDATE = [
        URI => 'module-update',
        VIEW => ''
    ];

    const GET_COORDINATES = [
        URI => 'city/get-coordinates',
        VIEW => ''
    ];

    const GET_ALL_ZONE_COORDINATES = [
        URI => 'get-all-city-coordinates',
        VIEW => ''
    ];
}
