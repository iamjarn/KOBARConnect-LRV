<?php

return [
    "SERVER_KEY"    => env("MIDTRANS_SERVER_KEY"),
    "IS_PRODUCTION" => !env("APP_DEBUG"),
    "payment_type"  => [
        "bank_transfer" => "bank_transfer",
        "echannel"  => "echannel",
        "permata"   => "permata",
        "gopay"   => "gopay"
    ],
    "payment_status"    => [
        "settlement"   => "PAID",
        "pending"      => "WAITING_PAYMENT",
        "deny"          => "REJECTED",
        "expire"        => "EXPIRED",
        "cancel"        => "CANCELED"
    ]
];
