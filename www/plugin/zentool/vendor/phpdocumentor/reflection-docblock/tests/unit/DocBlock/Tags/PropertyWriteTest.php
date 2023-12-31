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
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite
 * @covers ::<private>
 */
class PropertyWriteTest extends TestCase
{
    /**
     * Call Mockery::close after each test.
     */
    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfCorrectTagNameIsReturned(): void
    {
        $fixture = new PropertyWrite('myProperty', null, new Description('Description'));

        $this->assertSame('property-write', $fixture->getName());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite::__toString
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfTagCanBeRenderedUsingDefaultFormatter(): void
    {
        $fixture = new PropertyWrite('myProperty', new String_(), new Description('Description'));
        $this->assertSame('@property-write string $myProperty Description', $fixture->render());

        $fixture = new PropertyWrite('myProperty', null, new Description('Description'));
        $this->assertSame('@property-write $myProperty Description', $fixture->render());

        $fixture = new PropertyWrite('myProperty');
        $this->assertSame('@property-write $myProperty', $fixture->render());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     */
    public function testIfTagCanBeRenderedUsingSpecificFormatter(): void
    {
        $fixture = new PropertyWrite('myProperty');

        $formatter = m::mock(Formatter::class);
        $formatter->shouldReceive('format')->with($fixture)->andReturn('Rendered output');

        $this->assertSame('Rendered output', $fixture->render($formatter));
    }

    /**
     * @covers ::__construct
     * @covers ::getVariableName
     */
    public function testHasVariableName(): void
    {
        $expected = 'myProperty';

        $fixture = new PropertyWrite($expected);

        $this->assertSame($expected, $fixture->getVariableName());
    }

    /**
     * @covers ::__construct
     * @covers ::getType
     */
    public function testHasType(): void
    {
        $expected = new String_();

        $fixture = new PropertyWrite('myProperty', $expected);

        $this->assertSame($expected, $fixture->getType());
    }

    /**
     * @covers ::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getDescription
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     */
    public function testHasDescription(): void
    {
        $expected = new Description('Description');

        $fixture = new PropertyWrite('1.0', null, $expected);

        $this->assertSame($expected, $fixture->getDescription());
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     * @uses   \phpDocumentor\Reflection\Types\String_
     */
    public function testStringRepresentationIsReturned(): void
    {
        $fixture = new PropertyWrite('myProperty', new String_(), new Description('Description'));

        $this->assertSame('string $myProperty Description', (string) $fixture);
    }

    /**
     * @covers ::create
     * @uses \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite::<public>
     * @uses \phpDocumentor\Reflection\DocBlock\DescriptionFactory
     * @uses \phpDocumentor\Reflection\DocBlock\Description
     * @uses \phpDocumentor\Reflection\Types\Context
     */
    public function testFactoryMethod(): void
    {
        $typeResolver = new TypeResolver();
        $descriptionFactory = m::mock(DescriptionFactory::class);
        $context = new Context('');

        $description = new Description('My Description');
        $descriptionFactory->shouldReceive('create')->with('My Description', $context)->andReturn($description);

        $fixture = PropertyWrite::create(
            'string $myProperty My Description',
            $typeResolver,
            $descriptionFactory,
            $context
        );

        $this->assertSame('string $myProperty My Description', (string) $fixture);
        $this->assertSame('myProperty', $fixture->getVariableName());
        $this->assertInstanceOf(String_::class, $fixture->getType());
        $this->assertSame($description, $fixture->getDescription());
    }

    /**
     * @covers ::create
     * @uses \phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite::<public>
     * @uses \phpDocumentor\Reflection\TypeResolver
     * @uses \phpDocumentor\Reflection\DocBlock\DescriptionFactory
     */
    public function testFactoryMethodFailsIfEmptyBodyIsGiven(): void
    {
        $this->expectException('InvalidArgumentException');
        $descriptionFactory = m::mock(DescriptionFactory::class);
        PropertyWrite::create('', new TypeResolver(), $descriptionFactory);
    }

    /**
     * @covers ::create
     */
    public function testFactoryMethodFailsIfResolverIsNull(): void
    {
        $this->expectException('InvalidArgumentException');
        PropertyWrite::create('body');
    }

    /**
     * @covers ::create
     * @uses \phpDocumentor\Reflection\TypeResolver
     */
    public function testFactoryMethodFailsIfDescriptionFactoryIsNull(): void
    {
        $this->expectException('InvalidArgumentException');
        PropertyWrite::create('body', new TypeResolver());
    }
}
