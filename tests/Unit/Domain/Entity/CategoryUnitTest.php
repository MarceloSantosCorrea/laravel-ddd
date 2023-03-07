<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Exceptions\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CategoryUnitTest extends TestCase
{
    public function test_attributes()
    {
        $category = new \Core\Domain\Entity\Category(
            name: 'Category Test',
            description: 'description test',
            isActive: true,
        );

        $this->assertTrue(Uuid::isValid($category->id()));
        $this->assertEquals('Category Test', $category->name);
        $this->assertEquals('description test', $category->description);
        $this->assertTrue($category->isActive);
        $this->assertNotNull($category->createdAt());
    }

    public function test_activeted()
    {
        $category = new \Core\Domain\Entity\Category(
            name: 'Category Test',
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function test_disabled()
    {
        $category = new \Core\Domain\Entity\Category(
            name: 'Category Test',
        );

        $this->assertTrue($category->isActive);

        $category->disable();
        $this->assertFalse($category->isActive);
    }

    public function test_update()
    {
        $uuid = Uuid::uuid4()->toString();
        $category = new \Core\Domain\Entity\Category(
            id: $uuid,
            name: 'Category Test',
            description: 'description test',
            isActive: true,
        );

        $category->update(
            name: 'Category Test Edited',
            description: 'description test edited',
        );

        $this->assertEquals($uuid, $category->id);
        $this->assertEquals('Category Test Edited', $category->name);
        $this->assertEquals('description test edited', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function test_exception_name_null()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Should not be empty or null');

        new \Core\Domain\Entity\Category(
            name: '',
            description: 'description test',
        );

        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('nome deve conter entre 3 e 255 caracteres');

        new \Core\Domain\Entity\Category(
            name: 'Ca',
        );
    }

    public function test_exception_name_min_length()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must not be least 3 chatacters');

        new \Core\Domain\Entity\Category(
            name: 'Ca',
        );
    }

    public function test_exception_name_max_length()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must not be greater than 255 chatacters');

        new \Core\Domain\Entity\Category(
            name: 'Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Loremm',
        );
    }
}
