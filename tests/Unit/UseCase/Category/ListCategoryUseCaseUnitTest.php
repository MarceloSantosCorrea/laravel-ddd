<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryCreateOutputDto;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ListCategoryUseCaseUnitTest extends TestCase
{
    public function test_get_by_id()
    {
        $id = (string) Uuid::uuid4()->toString();
        $categoryName = 'Category Test';

        $mockEntity = \Mockery::mock(CategoryEntity::class, [
            $id,
            $categoryName,
        ]);
        $mockEntity->shouldReceive('id')->andReturn($id);
        $mockEntity->shouldReceive('createdAt')->andReturn((new \DateTime())->format('Y-m-d H:i:s'));

        $mockRepo = \Mockery::mock(\stdClass::class, CategoryRepositoryInterface::class);
        $mockRepo->shouldReceive('findById')->andReturn($mockEntity);

        $useCase = new ListCategoryUseCase($mockRepo);

        $mockInputDto = \Mockery::mock(CategoryInputDto::class, [
            $id,
        ]);

        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CategoryOutputDto::class, $responseUseCase);
        $this->assertEquals($id, $responseUseCase->id);
        $this->assertEquals($categoryName, $responseUseCase->name);

        $spy = \Mockery::spy(\stdClass::class, CategoryRepositoryInterface::class);
        $spy->shouldReceive('findById')->andReturn($mockEntity);

        $useCase = new ListCategoryUseCase($spy);
        $useCase->execute($mockInputDto);
        $spy->shouldHaveReceived('findById');

        \Mockery::close();
    }
}
