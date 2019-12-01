<?php

namespace Http\Response;

use phpmock\phpunit\PHPMock;
use PHPUnit_Framework_TestCase as TestCase;
use GuzzleHttp\Psr7\Response;

class SendTest extends TestCase
{
    use PHPMock;

    public function testSend()
    {
        $response = new Response(
            $status = 200,
            $headers = [
                'Content-Type' => 'text/plain',
            ],
            $body = uniqid(true),
            $version = '1.1'
        );

        $sent_headers = [];
        $header = $this->getFunctionMock(__NAMESPACE__, 'header');
        $header->expects($this->any())->will($this->returnCallback(
            function ($header, $replace) use (&$sent_headers) {
                return $sent_headers[] = $header;
            }
        ));

        $output = $this->captureSend($response);

        $this->assertSame([
            "HTTP/$version $status OK",
            "Content-Type: text/plain",
        ], $sent_headers);

        $this->assertSame($body, $output);
    }

    private function mockHeaderCalls()
    {
    }

    private function captureSend(Response $response)
    {
        ob_start();
        send($response);
        return ob_get_clean();
    }
}
