# PostValidator

maatify.dev JSON Codes handler, known by our team

# Installation

```shell
composer require maatify/json-code
```

## Important
Don't forget to create Class App\Assist\RegexPatterns

Don't forget to create Class App\Assist\AppFunctions

Don't forget to create Class App\Assist\MJCVarCodes

```php
<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2024-01-13
 * Time: 08:40:18
 * https://www.Maatify.dev
 */

namespace App\Assist;

class MJCVarCodes
{
    public static function Codes(string $varName): int
    {
        return match ($varName) {
            'action' => 10,
            'api' => 11,
            'app_type' => 12,
            'main_hash' => 13,
            'hash' => 14,
            'token' => 15,
            'device_id' => 16,
            'device_name' => 17,
            'device_version' => 18,
            'account_no' => 19,
            'phone' => 20,
            'email' => 21,
            'phone_or_email' => 22,
            'username' => 23,
            'code' => 24,
            'password' => 25,
            'old_password' => 26,
            'transaction_pin' => 27,
            'old_transaction_pin' => 28,
            'ip' => 29,
            'language' => 30,
            'national_id' => 31,
            'credentials' => 32,
            'first_name' => 33,
            'last_name' => 34,
            'name' => 35,
            'description' => 36,
            'status' => 37,
            'title' => 38,
            'birthdate' => 39,
            'gender' => 40,
            'country' => 41,
            'governorate' => 42,
            'city' => 43,
            'area' => 44,
            'address' => 45,
            'info' => 46,
            'base64_file' => 47,
            'file' => 48,
            default => 0,
        };
    }
}
