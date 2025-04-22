<?php

namespace Ababilitworld\FlexFoundationByAbabilitworld\Package\Foundation\Api\Firebase\Jwt\Helper;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Firebase\JWT\JWT as FireJWT;
use Firebase\JWT\Key as FireJWTKEY;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

if (!class_exists(__NAMESPACE__.'\Helper')) 
{
    /**
     * Class Helper
     *
     * @package \Ababilitworld\FlexFoundationByAbabilitworld\Package\Foundation\Api\Firebase\Jwt\Helper\Helper
     */
    class Helper
    {
        private static $key = "$?t.X] @ynuKCiy]zG!2E *1qRJ2|NCd 4;D21O%~P~RU^W@0K-=Ds2wj4s[OT_-";

        public static function generate_token($data, $expiration = 3600)
        {
            $issuedAt = time();
            $payload = [
                'iat' => $issuedAt,
                'exp' => $issuedAt + $expiration,
                'data' => $data,
            ];

            try
            {
                return FireJWT::encode($payload, self::$key, 'HS256');
            }
            catch (\Exception $e)
            {
                return null;
            }
        }

        public static function verify_request_token($token_name,$request)
        {
            try 
            {
                $data = $request->get_json_params();
                $headerToken = self::get_token_from_request($request);
                $token = $data[$token_name];
                if (!$token || self::is_token_invalid($token)) 
                {
                    return false;
                }
                return self::verify_token($token);
            }
            catch (\Exception $e) 
            {
                return false;
            }
        }

        public static function get_token_from_request($request)
        {
            $token = $request->get_header('Foundationorization');
            if (!$token || !preg_match('/Bearer\s(\S+)/', $token, $matches)) 
            {
                return false;
            }

            return $matches[1];
        }

        public static function verify_token($token)
        {
            try 
            {
                $decoded = FireJWT::decode($token, new FireJWTKEY(self::$key,'HS256'));
                if (is_string($decoded)) 
                {
                    return $decoded;
                }
                else
                {
                    if (isset($decoded->data)) 
                    {
                        return (array) $decoded->data;
                    }
                    else
                    {
                        return 'Data field not found in token payload';
                    }
                }
            }
            catch (InvalidArgumentException $e) 
            {
                // provided key/key-array is empty or malformed.
            }
            catch (DomainException $e) 
            {
                // provided algorithm is unsupported OR
                // provided key is invalid OR
                // unknown error thrown in openSSL or libsodium OR
                // libsodium is required but not available.
            }
            catch (SignatureInvalidException $e) 
            {
                // provided JWT signature verification failed.
            }
            catch (BeforeValidException $e) 
            {
                // provided JWT is trying to be used before "nbf" claim OR
                // provided JWT is trying to be used before "iat" claim.
            }
            catch (ExpiredException $e) 
            {
                // provided JWT is trying to be used after "exp" claim.
            }
            catch (UnexpectedValueException $e) 
            {
                // provided JWT is malformed OR
                // provided JWT is missing an algorithm / using an unsupported algorithm OR
                // provided JWT algorithm does not match provided key OR
                // provided key ID in key/key-array is empty or invalid.
            }
        }

        public static function force_invalidate_token($token)
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'jwt_invalid_tokens';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
            {
                $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    token varchar(255) NOT NULL,
                    created_at datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY  (id)
                ) $charset_collate;";
                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
            }
            $wpdb->insert($table_name, ['token' => $token]);
        }

        public static function is_token_invalid($token)
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'jwt_invalid_tokens';
            $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE token = %s", $token));
            return $result !== null;
        }
    }
}