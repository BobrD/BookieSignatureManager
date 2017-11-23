<?php

namespace Bookie\SignatureManager;

class SignatureManager
{
    /**
     * @var string
     */
    private $secretFieldName;

    public function __construct(string $secretFieldName)
    {
        $this->secretFieldName = $secretFieldName;
    }

    /**
     * @param string $secret
     * @param string $signature
     * @param array  $params
     *
     * @return bool
     */
    public function verify($secret, $signature, array $params)
    {
        return $signature === $this->generate($secret, $params);
    }

    /**
     * @param string $secret
     * @param array  $params
     *
     * @return string
     */
    public function generate($secret, array $params)
    {
        if (array_key_exists('signature', $params)) {
            unset($params['signature']);
        }

        $params[$this->secretFieldName] = $secret;

        $params = $this->splashArray($params);

        ksort($params);

        $params = $this->castToString($params);

        return md5(implode($params));
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function castToString(array $params)
    {
        $casted = [];
        foreach ($params as $key => $value) {
            $casted[$key] = (string) $value;
        }

        return $casted;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function splashArray($array)
    {
        $flattenArray = function (array $array, $prevKey = null) use (&$flattenArray) {
            $result = [];

            foreach ($array as $key => $value) {
                $resultKey = $prevKey ? $prevKey.'_'.$key : $key;

                if (is_array($value)) {
                    $result = array_merge($result, $flattenArray($value, $resultKey));
                    continue;
                }

                $result[$resultKey] = $value;
            }

            return $result;
        };

        return $flattenArray($array);
    }
}
