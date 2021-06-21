<?php 
declare(strict_types=1);
namespace GreenFedora\Uri\Tests;

use PHPUnit\Framework\TestCase;

use GreenFedora\Uri\Uri;

final class UriTest extends TestCase
{
    public function testObjectCreationAndRetrieveUri()
    {
        $uri = new Uri("https://example.com/base/deep/this-is-a-file");

        $this->assertInstanceOf(Uri::class, $uri, "Is instance of URI.");

        $this->assertEquals("https://example.com/base/deep/this-is-a-file", $uri->getUri());
    }
}