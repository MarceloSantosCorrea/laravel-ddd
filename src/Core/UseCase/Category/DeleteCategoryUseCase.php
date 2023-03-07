<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryDeleteInputDto;
use Core\UseCase\DTO\Category\CategoryDeleteOutputDto;

class DeleteCategoryUseCase
{
    protected CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryDeleteInputDto $input): CategoryDeleteOutputDto
    {
        $category = $this->repository->findById($input->id);

        return new CategoryDeleteOutputDto(
            success: $this->repository->delete($category->id())
        );
    }
}