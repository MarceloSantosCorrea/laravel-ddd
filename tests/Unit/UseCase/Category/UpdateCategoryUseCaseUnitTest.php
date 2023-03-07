<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDto;
use Core\UseCase\DTO\Category\CategoryUpdateOutputDto;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    public function test_update_category(): void
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
        $mockEntity->shouldReceive('createdAt')->andReturn((new \DateTime())->format('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update');

        $mockRepository = \Mockery::mock(\stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')->andReturn($mockEntity);

        $mockInputDto = \Mockery::mock(CategoryUpdateInputDto::class, [
            $id,
            'new name',
            'new description'
        ]);

        $useCase = new UpdateCategoryUseCase($mockRepository);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CategoryUpdateOutputDto::class, $responseUseCase);


        $mockRepositorySpy = \Mockery::spy(\stdClass::class, CategoryRepositoryInterface::class);
        $mockRepositorySpy->shouldReceive('findById')->andReturn($mockEntity);
        $mockRepositorySpy->shouldReceive('update')->andReturn($mockEntity);

        $useCase = new UpdateCategoryUseCase($mockRepositorySpy);
        $useCase->execute($mockInputDto);

        $mockRepositorySpy->shouldHaveReceived('findById');
        $mockRepositorySpy->shouldHaveReceived('update');

        \Mockery::close();
    }
}
