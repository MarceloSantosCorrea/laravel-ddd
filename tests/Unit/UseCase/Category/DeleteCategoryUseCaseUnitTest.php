<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryDeleteInputDto;
use Core\UseCase\DTO\Category\CategoryDeleteOutputDto;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function test_delete_category(): void
    {
        $id = Uuid::uuid4()->toString();
        $categoryName = 'Category Name Test';
        $categoryDescription = 'Category Description Test';

        $mockEntity = \Mockery::mock(EntityCategory::class, [
            $id,
            $categoryName,
            $categoryDescription
        ]);
        $mockEntity->shouldReceive('id')->andReturn($id);

        $mockRepository = \Mockery::mock(\stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->andReturn($mockEntity);
        $mockRepository->shouldReceive('delete')->andReturn(true);

        $mockInputDto = \Mockery::mock(CategoryDeleteInputDto::class, [
            $id
        ]);

        $useCase = new DeleteCategoryUseCase($mockRepository);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class, $responseUseCase);

        $mockRepositorySpy = \Mockery::spy(\stdClass::class, CategoryRepositoryInterface::class);
        $mockRepositorySpy->shouldReceive('findById')->andReturn($mockEntity);
        $mockRepositorySpy->shouldReceive('delete')->andReturn(true);

        $useCase = new DeleteCategoryUseCase($mockRepositorySpy);
        $useCase->execute($mockInputDto);

        $mockRepositorySpy->shouldHaveReceived('findById');
        $mockRepositorySpy->shouldHaveReceived('delete');

        \Mockery::close();
    }
}
