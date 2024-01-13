<?php

/**
 * @copyright   ©2024 Maatify.dev
 * @Liberary    Json-Code
 * @Project     Json-Code
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2024-01-13 08:03 AM
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/Json-Code view project on GitHub
 * @link        https://github.com/Maatify/Functions (maatify/functions)
 * @link        https://github.com/Maatify/Logger (maatify/logger)
 * @note        This Project using for Response Json wih error Code.
 * @note        This Project extends other libraries maatify/logger, maatify/functions.
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

namespace Maatify\JsonCode;

use Maatify\Functions\GeneralFunctions;
use \App\Assist\MJCVarCodes;

class MJC extends JsonGeneralResponse
{
    protected static int|string $line;
    private static self $instance;

    public static function obj(): self
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }
        self::$line = debug_backtrace()[0]['line'];

        return self::$instance;
    }

    public static function Missing(
        string $varName,
        string $moreInfo = '',
        int|string $line = ''
    ): void
    {
        self::PostErrorHandler($varName, 1000, $moreInfo, $line);
    }

    public static function Incorrect(
        string $varName,
        string $moreInfo = '',
        int|string $line = ''
    ): void
    {
        self::PostErrorHandler($varName, 2000, $moreInfo, $line);
    }

    public static function Exist(string $varName, string $moreInfo = '', int|string $line = ''): void
    {
        self::PostErrorHandler($varName, 3000, $moreInfo, $line);
    }

    public static function Invalid(
        string $varName,
        string $moreInfo = '',
        int|string $line = ''
    ): void
    {
        self::PostErrorHandler($varName, 4000, $moreInfo, $line);
    }

    public static function NotVerified(string $varName, string $moreInfo = '', int|string $line = ''): void
    {
        self::PostErrorHandler($varName, 5000, $moreInfo, $line);
    }

    public static function NotExist(string $varName, string $moreInfo = '', int|string $line = ''): void
    {
        self::PostErrorHandler($varName, 6000, $moreInfo, $line);
    }

    public static function NotAllowedToUse(string $varName, string $moreInfo = '', int|string $line = ''): void
    {
        self::PostErrorHandler($varName, 7000, $moreInfo, $line);
    }

    public static function Success(array $result = [], string $description = '', string $more_info = '', int|string $line = ''): void
    {
        self::HeaderResponseJson([
            'success'       => true,
            'response'      => 200,
            'result'        => $result,
            'description'   => $description,
            'more_info'     => $more_info,
            'error_details' => GeneralFunctions::CurrentPageError($line ? : debug_backtrace()[0]['line']),
        ]);
    }

    private static function PostErrorHandler(
        string $varName,
        int $CodeRange,
        string $moreInfo = '',
        int|string $line = ''
    ): void
    {
        self::ErrorWithHeader400(
            self::InputCode($varName) + $CodeRange,
            $varName,
            self::ErrorDescription($varName, $CodeRange),
            $moreInfo,
            $line
        );
    }

    private static function Codes(string $varName): int
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

    private static function InputCode(string $varName): int
    {
        if (! $code = MJCVarCodes::Codes($varName)) {
            $code = self::Codes($varName);
        }

        return $code;
    }

    private static function ErrorDescription(
        string $varName,
        int $CodeRange
    ): string
    {
        return str_replace(
            '??',
            ucwords(str_replace('_', ' ', $varName), ' '),
            match ($CodeRange) {
                1000 => 'MISSING ??',
                2000 => 'Incorrect ??',
                3000 => '?? is already exist',
                4000 => 'INVALID ??',
                5000 => '?? is Not verified',
                6000 => '?? is Not exist',
                7000 => '?? is Not Allowed To Use',
                default => '',
            }
        );
    }

    public static function DbError(int|string $line = 0): void
    {
        http_response_code(500);
        self::HeaderResponseJson([
            'success'     => false,
            'response'    => 500,
            'description' => 'Internal Server Error',
            'more_info'   => $line ? : '',
        ]);
    }

    public static function JsonFormat($array): string
    {
        return (string)str_replace(array("\r", "\n"), '', json_encode($array, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_UNESCAPED_SLASHES));
    }

}