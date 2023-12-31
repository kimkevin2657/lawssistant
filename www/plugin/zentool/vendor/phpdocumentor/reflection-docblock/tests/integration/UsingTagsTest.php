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

namespace phpDocumentor\Reflection;

use Mockery as m;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tag;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class UsingTagsTest extends TestCase
{
    /**
     * Call Mockery::close after each test.
     */
    public function tearDown(): void
    {
        m::close();
    }

    public function testAddingYourOwnTagUsingAStaticMethodAsFactory(): void
    {
        /** @var object[] $customTagObjects */
        $customTagObjects;
        /** @var string $docComment */
        $docComment;
        /** @var string $reconstitutedDocComment */
        $reconstitutedDocComment;

        include(__DIR__ . '/../../examples/04-adding-your-own-tag.php');

        $this->assertInstanceOf(\MyTag::class, $customTagObjects[0]);
        $this->assertSame('my-tag', $customTagObjects[0]->getName());
        $this->assertSame('I have a description', (string) $customTagObjects[0]->getDescription());
        $this->assertSame($docComment, $reconstitutedDocComment);
    }
}
