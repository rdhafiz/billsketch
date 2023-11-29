<?php

namespace App\Repositories;

use App\Models\Categories;
use App\Models\User;

class CategoriesRepositories
{
    /**
     * @param array $categoryData
     * @return array|Categories
     */
    public static function save(array $categoryData): array|Categories
    {
        $categoryModel = new Categories();
        $categoryModel->user_id = $categoryData['user_id'];
        $categoryModel->name = $categoryData['name'];
        $categoryModel->color = $categoryData['color'] ?? null;
        $categoryModel->icon = $categoryData['icon'] ?? null;
        if (!$categoryModel->save()) {
            return ['message' => 'Cannot save category'];
        }
        return $categoryModel;
    }

    /**
     * @param Categories $categoryModel
     * @param array $categoryData
     * @return array|Categories
     */
    public static function update(Categories $categoryModel, array $categoryData): array|Categories
    {
        $categoryModel->name = $categoryData['name'];
        $categoryModel->color = $categoryData['color'] ?? null;
        if (!empty($categoryData['icon'])) {
            $categoryModel->icon = $categoryData['icon'];
        }
        if (!$categoryModel->save()) {
            return ['message' => 'Cannot update category'];
        }
        return $categoryModel;
    }
    /**
     * @param integer $categoryId
     * @return array|Categories
    */
    public static function single(int $categoryId): array|Categories
    {
        $category = Categories::select('id', 'name', 'color', 'icon')->where('id', $categoryId)->first();
        if (!$category instanceof Categories) {
            return ['message' => 'Cannot find category'];
        }
        return $category;
    }

    /**
     * @param array $filter
     * @param array $pagination
     * @param User $user
     * @return mixed
     */
    public static function list(array $filter, array $pagination, User $user): mixed
    {
        $result = Categories::select('name', 'icon', 'color')
            ->where('user_id', $user['id']);
        if (!empty($filter['list_type']) && $filter['list_type'] == 'archive') {
            $result->where('is_active', 0);
        } else {
            $result->where('is_active', 1);
        }
        if (!empty($filter['keyword'])) {
            $result->where(function($q) use ($filter) {
                $q->where('name', 'LIKE', '%'.$filter['keyword'].'%');
            });
        }
        return $result->orderBy($pagination['order_by'], $pagination['order_mode'])->paginate($pagination['limit']);
    }
}
