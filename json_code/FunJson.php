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
use Maatify\Logger\Logger;

abstract class FunJson
{
    public static function PostLogger(): void
    {
        $arr = $_POST;
        if (isset($_POST['password']) & ! empty($_POST['password'])) {
            $arr['password'] = '*********';
        }
        Logger::RecordLog(['posted_data' => ($arr ?? ''),
                           'agent'       => ($_SERVER['HTTP_USER_AGENT']) ?? '',
                           'ip'          => $_SERVER['REMOTE_ADDR'] ?? '',
                           'forward_ip'  => ($_SERVER['HTTP_X_FORWARDED_FOR'] ??
                                             ''),
                           'server_ip'   => ($_SERVER['SERVER_ADDR'] ?? ''),
                           'referer'     => ($_SERVER['HTTP_REFERER'] ?? ''),
                           'uri'         => ($_SERVER['REQUEST_URI'] ?? ''),
                           'page'        => (basename($_SERVER['PHP_SELF']) ??
                                             ''),
        ], 'post/' . (basename($_SERVER["PHP_SELF"], '.php') ?? 'posted'));
    }

    public static function LoggerResponseAndPost($json_array): void
    {
        $arr = $_POST;
        if (isset($_POST['password']) & ! empty($_POST['password'])) {
            $arr['password'] = '*******';
        }

        if (! empty($_POST['base64_file'])) {
            if (base64_encode(base64_decode($_POST['base64_file'], true)) === $_POST['base64_file']) {
                $arr['base64_file'] = 'Valid Base64';
            } else {
                $arr['base64_file'] = 'Not Valid Base64';
            }
        }
        if (isset($json_array['result']['base64'])) {
            unset($json_array['result']['base64']);
        }

        // Handle Logger APP Folder
        $url = $_SERVER['REQUEST_URI'];
        $url = ltrim($url, '/');
        $urlParts = explode('/', $url);
        $app_type = $urlParts[0] ?? '';
        if(!empty($app_type) && $app_type == 'apps'){
            $app_folder_logger = ($urlParts[0] ?? '') . '-' . ($urlParts[1] ?? '');
        }else{
            $app_folder_logger = $urlParts[0] ?? '';
        }


        Logger::RecordLog(['Response'    => $json_array,
                           'posted_data' => ($arr ?? ''),
                           'agent'       => $_SERVER['HTTP_USER_AGENT'],
                           'ip'          => $_SERVER['REMOTE_ADDR'] ?? '',
                           'real_ip'     => $_SERVER['REMOTE_ADDR'],
                           'forward_ip'  => ($_SERVER['HTTP_X_FORWARDED_FOR'] ??
                                             ''),
                           'server_ip'   => ($_SERVER['SERVER_ADDR'] ?? ''),
                           'referer'     => ($_SERVER['HTTP_REFERER'] ?? ''),
                           'uri'         => ($_SERVER['REQUEST_URI'] ?? ''),
                           'page'        => (basename($_SERVER['PHP_SELF']) ??
                                             ''),
        ],
            'post/' .
            ($app_folder_logger ? $app_folder_logger . '/' : '') .
            (basename($_SERVER["PHP_SELF"], '.php') ?? 'posted') .
            '_response');
    }


    public static function HeaderError($line = ''): void
    {
        self::ErrorWithHeader400(1000001,'',
            'Error: ' . GeneralFunctions::CurrentPageError($line), line: $line);
    }

    public static function HeaderResponseError($code, $desc, $more = '', $line = ''): void
    {
        self::HeaderResponseJson(['success'     => false,
                                  'response'    => $code,
                                  'description' => $desc,
                                  'more_info'   => $more,
                                  'error_details'   => GeneralFunctions::CurrentPageError($line),]);
    }

    public static function headerResponseSuccess($arr): void
    {

        $result['success'] = true;
        $result['response'] = 200;
        if (isset($arr['token'])) {
            $result['token'] = $arr['token'];
            unset($arr['token']);
        }
        foreach ($arr as $key => $value) {
            $result[$key] = $value;
        }
        $result['description'] = '';
        self::HeaderResponseJson($result);
    }

    public static function HeaderResponseJson($json_array): void
    {
        if (! empty($_ENV['JSON_POST_LOG'])) {
            self::LoggerResponseAndPost($json_array);
        }
        header('Content-type: application/json; charset=utf-8');
        echo(json_encode($json_array,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES));
        exit();
    }

    public static function ErrorWithHeader400(int $responseCode,
        string $varName,
        string $description = '',
        string $moreInfo = '',
        int|string $line = ''
    ): void
    {
        http_response_code(400);
        self::HeaderResponseJson([
            'success'     => false,
            'response'    => $responseCode,
            'var'    => $varName,
            'description' => $description,
            'more_info'   => $moreInfo,
            'error_details'   => GeneralFunctions::CurrentPageError($line),
        ]);
    }
}