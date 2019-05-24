<?php

/**
 * 微信支付与支付宝支付接口配置信息 额外的 :
 * 支付宝沙箱买家账号 qcqcgu5276@sandbox.com
 */

return [
    // 支付宝支付配置信息
    'alipay' => [
        'app_id'         => '2016093000633455',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA40dCR7sYY7tXAT2BG/Ael281vHY+nAAtTVf4fM9b+re6A9w73OxfGyA2tk4SMfty5J6hoTkKR+XV8R+hE3fbDzSc5vyGHvsLA2duQ1qwh4W4MZKCZ8wSWKF9qN9442jLO7O6BqjkqesZSp6I6Pue/v1E8AxLYw4A/nLF+wtIZykzsnmMMnjhYaKfXeNgW92Creo8DxbDa8wK7bKmV9vTMKqQh70XSYun72XTHMLN9FIEIvxGWvbzWjEDzSGf9pEJRtBwy/kVVavl6PlSR3RtNhsR+Lv05Br6OexWJWV2kYzxpUe3sKU1ImvZlPpq/cq46/mOP8fry3gSSdoIzLchiwIDAQAB',
        'private_key'    => 'MIIEpQIBAAKCAQEA2rFboHSKowLw3We/PpTWz+LT4RC2qC7qKFyEpWS7nTq7FnsdmwPp/F+/dBFW1thAMspobfywsxgzdMus2kolyjTwGNL1alFOCCZDTin3px2cDTrQtNhlnvWAVjTUkBEiyaTwKJ8u2UmirwBzCtsa7r10R7zSa5MoAvQ9pBo5CIKjWYeve4xHh9bjqOX9yCSrzvUGWKR0HZdOnYns7y/Bwf/69p/u85FXaOtbWfdBc7S9fPjOlYuMVP+GunyQxub9Mkpqq1OmZMgOZssSoz2vFEE2OuY8QniTSC5ot3qs9MCxBKlb3dNW4pT89409WOdwYMRkQy136Jwgb0m0YfqrRwIDAQABAoIBAAdysuQsE+ypVPq1ZdYDy60Y1I59uzrPHjpxrDLV023vah/B4ag94eIugJ67WHHIu6b/Lb9RM9Nlp5r6tNBaZh8U3nh59tHAIyAz7WbkFwMiBwaMUj9Wf1+CjPRiqDqf2sdzTpqTkUzlLR04rrJX8/rdiPBIhgIT6PDw0IiePAMGH9YcmSAp2OdRbQth4BnG5bsJJ+q7MUa4fWxJKeUJIL4JdlW/XmF2mjy9wZ067ZeUqbsqpuAO0mxqKNM0G1NJ5G4SpMBDAZITBPLaFhmiRYcJl7qdUXO3srmdu5ZDmaks8CWqKs8f7mPg7Csjk5VKu3guSxPNXG+eOdfUVkeCAMECgYEA+cUVBhhY6Nvp5wnEVUTG4nkhnouxA8I1o0wMwII7Y7+CibYJdV03zHxX0aoOxI3V+4c4KR7nd3nJvuaz03GuaJvHhOKE9v97Rowd+k3OLCfyNf6yYaENCEju3al/1ypeEpHX1jK7SwMUXJdMHYujHtRerMpg+9edyK+kLNAWi+UCgYEA4CXU7aiYLeg3ugFvQGjfOCN80N/c9S8izLhrB+379mvBwhakrrXklngL7Vvjk4yboAeZ3+BsvjvyMpEnatJUI7tR8WEmj52pkQtq690xNp1/xfI3+bV5fCpSNxkVc/stP6QZ9IuVHrR0sVgGi/mAylUkMi9X7UXoJ1dA0Iiu37sCgYEAly0Ni5HjBpZM3bVQq1eklWFB0we0DZVYB825DjAFKFu69AVFdk8EvfUo5lNq+tbqhIKUoHb/HJaPYMecYke1i8V2Ht1II4QBJnMalezP9HP9K2dMqsDi9XfHdEzqft468DxsCk7MhBuKEHzW6i8hfUCHSUh5KRWZf+WnliQg8W0CgYEAk60ZLkNHj+dQ38GWNI9jka7/yvXSs88MVGikS6Mlv+Ka027rrRe1tNT+trE1rL6UwciAPpmzy1nfZiVwHNbaDHibsjZZm4E6eI9XMHKYu+zkg9vjuScxFPclF5v1IGa6FxXc69sSshoeT8E4/vuxOJ0DrfS3L2JBY7n+v+VtoYUCgYEAyDXt/MYoUyTnKRhGL2CLL3I8f+OFYXPuU1Tnfmcz8Io75t/G2xlE/WfYpc3l0P3mjjJPhKUQIavkW7qA8QLIyijPldhj9ZPwJcezCdtAF0EU2r21t5IluG8u572IVg7HzIUFaC4fhGzFmarziEIqvVbQjuQjjGK8iKyzbgGC83g=',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],
    // 微信支付配置信息
    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
