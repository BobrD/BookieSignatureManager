<?php

namespace Tests\Bookie\SignatureManager;

use Bookie\SignatureManager\SignatureManager;
use PHPUnit\Framework\TestCase;

class SignatureManagerTest extends TestCase
{

    private $filed = 'secret';

    private $secret = 'secret_key';


    public function testGenerate()
    {
        $manager = new SignatureManager($this->filed);

        $params = ['method' => 'some_method', 'username' => 'some_username'];

        $signature = $manager->generate($this->secret, $params);

        $params[$this->filed] = $this->secret;
        ksort($params);
        $expected = md5(implode($params));

        static::assertEquals($expected, $signature);
    }

    public function testVerify()
    {
        $manager = new SignatureManager($this->filed);

        $params = [
            'method' => 'some_method',
            'user_token' => 'some_token',
            'time' => time(),
        ];

        $params[$this->filed] = $this->secret;
        ksort($params);
        $signature = md5(implode($params));

        $params['signature'] = $signature;

        $result = $manager->verify($this->secret, $signature, $params);

        print_r($params);

        static::assertTrue($result);
    }
}
