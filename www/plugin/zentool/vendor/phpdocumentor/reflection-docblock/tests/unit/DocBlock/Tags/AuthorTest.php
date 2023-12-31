<?php declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright 2010-2018 Mike van Riel<mike@phpdoc.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Reflection\DocBlock\Tags;

use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \phpDocumentor\Reflection\DocBlock\Tags\Author
 * @covers ::<private>
 */
class AuthorTest extends TestCase
{
    /**
     * Call Mockery::close after each test.
     */
    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Author::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfCorrectTagNameIsReturned(): void
    {
        $fixture = new Author('Mike van Riel', 'mike@phpdoc.org');

        $this->assertSame('author', $fixture->getName());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Author::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Author::__toString
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfTagCanBeRenderedUsingDefaultFormatter(): void
    {
        $fixture = new Author('Mike van Riel', 'mike@phpdoc.org');

        $this->assertSame('@author Mike van Riel <mike@phpdoc.org>', $fixture->render());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Author::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     */
    public function testIfTagCanBeRenderedUsingSpecificFormatter(): void
    {
        $fixture = new Author('Mike van Riel', 'mike@phpdoc.org');

        $formatter = m::mock(Formatter::class);
        $formatter->shouldReceive('format')->with($fixture)->andReturn('Rendered output');

        $this->assertSame('Rendered output', $fixture->render($formatter));
    }

    /**
     * @covers ::__construct
     * @covers ::getAuthorName
     */
    public function testHasTheAuthorName(): void
    {
        $expected = 'Mike van Riel';

        $fixture = new Author($expected, 'mike@phpdoc.org');

        $this->assertSame($expected, $fixture->getAuthorName());
    }

    /**
     * @covers ::__construct
     * @covers ::getEmail
     */
    public function testHasTheAuthorMailAddress(): void
    {
        $expected = 'mike@phpdoc.org';

        $fixture = new Author('Mike van Riel', $expected);

        $this->assertSame($expected, $fixture->getEmail());
    }

    /**
     * @covers ::__construct
     */
    public function testInitializationFailsIfEmailIsNotValid(): void
    {
        $this->expectException('InvalidArgumentException');
        new Author('Mike van Riel', 'mike');
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testStringRepresentationIsReturned(): void
    {
        $fixture = new Author('Mike van Riel', 'mike@phpdoc.org');

        $this->assertSame('Mike van Riel <mike@phpdoc.org>', (string) $fixture);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testStringRepresentationWithEmtpyEmail(): void
    {
        $fixture = new Author('Mike van Riel', '');

        $this->assertSame('Mike van Riel', (string) $fixture);
    }

    /**
     * @covers ::create
     * @uses \phpDocumentor\Reflection\DocBlock\Tags\Author::<public>
     */
    public function testFactoryMethod(): void
    {
        $fixture = Author::create('Mike van Riel <mike@phpdoc.org>');

        $this->assertSame('Mike van Riel <mike@phpdoc.org>', (string) $fixture);
        $this->assertSame('Mike van Riel', $fixture->getAuthorName());
        $this->assertSame('mike@phpdoc.org', $fixture->getEmail());
    }

    /**
     * @covers ::create
     * @uses \phpDocumentor\Reflection\DocBlock\Tags\Author::<public>
     */
    public function testFactoryMethodReturnsNullIfItCouldNotReadBody(): void
    {
        $this->assertNull(Author::create('dfgr<'));
    }
}
