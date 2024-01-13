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
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 */
namespace Maatify\JsonCode;

use Maatify\Functions\GeneralFunctions;

abstract class JsonGeneralResponse extends FunJson
{

    public static function ErrorNoUpdate(int|string $line = 0): void
    {
        self::ErrorWithHeader400(40001, 'There is no date to update', $line);
    }

    public static function Unauthorized(string $line = ''): void
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-type: application/json; charset=utf-8');
        echo(json_encode(['line'=>$line],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES));
        exit;
    }

    public static function ReLogin(int|string $line): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        self::GoToMethod('Login',
            'Please Re-Login',
            line: $line);
    }

    public static function GoToMethod(string $method, string $description = 'Go Back', string|array $more_info = '', int|string $line = 0, string $token = ''): void
    {
        self::HeaderResponseJson([
            'success'     => false,
            'response'    => 405000,
            'result' => ['token' => $token],
            'description' => $description,
            'more_info'   => $more_info,
            'action'   => $method,
            'error_details'   => GeneralFunctions::CurrentPageError($line ?: debug_backtrace()[0]['line']),

        ]);
    }

    public static function IpIsBlocked(): void
    {
        self::ErrorWithHeader400(403019, 'IP Blocked');
    }

    public static function MissingMethod(): void
    {
        header('HTTP/1.1 405 Method Not Allowed');
        exit;
    }

    public static function RenewalRequired(): void
    {
        header('HTTP/1.1 402 Payment Required, Expired account ');
        exit;
    }

    public static function UnauthorizedBlock(): void
    {
        header('HTTP/1.1 401 Unauthorized');
        exit;
    }

    public static function Forbidden(int|string $line = 0): void
    {
        header('HTTP/1.1 403 Forbidden');
        self::HeaderResponseJson([
            'success'     => false,
            'error_details'   => GeneralFunctions::CurrentPageError($line),
        ]);
        exit;
    }

    public static function DisabledAccount(): void
    {
        header('HTTP/1.1 409 Disabled account ');
        exit;
    }

    public static function IncorrectCredentials(int|string $line = 0): void
    {
        self::ErrorWithHeader400(2025, 'credentials', 'Incorrect Credentials', line: $line ?: debug_backtrace()[0]['line']);
    }

    public static function SuspendedAccount(int|string $line = ''): void
    {
        self::HeaderResponseError(403022, 'Suspended Account' , line: $line ?: debug_backtrace()[0]['line']);
    }

    public static function ApprovalPendingAccount(int|string $line = 0): void
    {
        self::HeaderResponseError(405022, 'Approval Pending Account' , line: $line ?: debug_backtrace()[0]['line']);
    }

    public static function PhoneAlreadyVerified(): void
    {
        self::ErrorWithHeader400(501601, 'phone', 'Already Verified');
    }

    public static function EmailAlreadyVerified(int|string $line = 0): void
    {
        self::ErrorWithHeader400(503030, 'email', 'Email Already Verified' , line: $line ?: debug_backtrace()[0]['line']);
    }

    public static function DeviceIsBlocked(): void
    {
        self::ErrorWithHeader400(403015, 'device', 'Device Blocked');
    }

    public static function DeviceIsPending(): void
    {
        self::ErrorWithHeader400(405015, 'device', 'Device Need Approve, Please Call Customer Support');
    }

    public static function GoBackStep(string $description = 'Go Back', int|string $line = 0): void
    {
        self::ErrorWithHeader400(30400, '', $description, (string)$line);
    }

    public static function TryAgain(int|string $line = 0): void
    {
        self::ErrorWithHeader400(30500, '', 'Please try again', line: $line ?: debug_backtrace()[0]['line']);
    }
}