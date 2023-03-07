<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\ListCategoriesUserCase;
use Core\UseCase\DTO\Category\ListCategoriesInputDto;
use Core\UseCase\DTO\Category\ListCategoriesOutputDto;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListCategoriesUserCaseUnitTest extends TestCase
{
    public function test_list_categories_empty()
    {
        $register = new stdClass();
        $register->id = 'id';
        $register->name = 'name';
        $register->description = 'description';
        $register->is_active = 'is_active';
        $register->created_at = 'created_at';
        $register->updated_at = 'updated_at';
        $register->deleted_at = 'deleted_at';

        $mockPagination = $this->mockPagination([
            $register
        ]);

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->andReturn($mockPagination);

        $mockInputDto = Mockery::mock(ListCategoriesInputDto::class, [
            'filter', 'desc'
        ]);

        $useCase = new ListCategoriesUserCase($mockRepository);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertCount(1, $responseUseCase->items);
        $this->assertInstanceOf(stdClass::class, $responseUseCase->items[0]);
        $this->assertEquals(0, $responseUseCase->total);
        $this->assertInstanceOf(ListCategoriesOutputDto::class, $responseUseCase);

        $mockSpy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $mockSpy->shouldReceive('paginate')->andReturn($mockPagination);
        $useCase = new ListCategoriesUserCase($mockSpy);
        $useCase->execute($mockInputDto);
        $mockSpy->shouldHaveReceived('paginate');
    }

    protected function mockPagination(
        array $items = []
    ): MockInterface|PaginationInterface|LegacyMockInterface
    {
        $mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $mockPagination->shouldReceive('items')->andReturn($items);
        $mockPagination->shouldReceive('total')->andReturn(0);
        $mockPagination->shouldReceive('lastPage')->andReturn(0);
        $mockPagination->shouldReceive('firstPage')->andReturn(0);
        $mockPagination->shouldReceive('currentPage')->andReturn(0);
        $mockPagination->shouldReceive('perPage')->andReturn(0);
        $mockPagination->shouldReceive('to')->andReturn(0);
        $mockPagination->shouldReceive('from')->andReturn(0);

        return $mockPagination;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
