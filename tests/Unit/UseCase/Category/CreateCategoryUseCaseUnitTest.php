<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryCreateOutputDto;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function test_create_new_category()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = 'Category Test';

        $mockEntity = \Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
        ]);
        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn((new \DateTime())->format('Y-m-d H:i:s'));

        $mockRepo = \Mockery::mock(\stdClass::class, CategoryRepositoryInterface::class);
        $mockRepo->shouldReceive('insert')->andReturn($mockEntity);

        $useCase = new CreateCategoryUseCase($mockRepo);

        $mockInputDto = \Mockery::mock(CategoryCreateInputDto::class, [
            $categoryName,
        ]);

        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CategoryCreateOutputDto::class, $responseUseCase);
        $this->assertEquals($uuid, $responseUseCase->id);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);
        $this->assertTrue($responseUseCase->is_active);

        $spy = \Mockery::spy(\stdClass::class, CategoryRepositoryInterface::class);
        $spy->shouldReceive('insert')->andReturn($mockEntity);
        $useCase = new CreateCategoryUseCase($spy);
        $useCase->execute($mockInputDto);

        $spy->shouldHaveReceived('insert');

        \Mockery::close();
    }
}
