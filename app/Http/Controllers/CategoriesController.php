<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Categories;
use App\Models\Invoices;
use App\Repositories\CategoriesRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $categoryData = [
                'user_id' => $requestData['session_user']['id'],
                'name' => $requestData['name'],
                'color' => $requestData['color'] ?? null,
            ];
            if ($request->file('icon')) {
                $categoryData['icon'] = Helpers::fileUpload($request->file('icon'));
            }
            $categoryInfo = CategoriesRepositories::save($categoryData);
            if (!$categoryInfo instanceof Categories) {
                return response()->json(['status' => 500, 'message' => 'Cannot save category'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Category_create);
            return response()->json(['status' => 200, 'message' => 'Category has been created successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $category = Categories::find($requestData['id']);
            if (!$category instanceof Categories) {
                return response()->json(['status' => 500, 'message' => 'Cannot find category'], 200);
            }
            $categoryData = [
                'id' => $requestData['id'],
                'name' => $requestData['name'],
                'color' => $requestData['color'] ?? null,
            ];
            if ($request->file('icon')) {
                Helpers::fileRemove($category, 'icon');
                $categoryData['icon'] = Helpers::fileUpload($request->file('icon'));
            }
            $categoryInfo = CategoriesRepositories::update($category, $categoryData);
            if (!$categoryInfo instanceof Categories) {
                return response()->json(['status' => 500, 'message' => 'Cannot update category'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Category_update);
            return response()->json(['status' => 200, 'message' => 'Category has been updated successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function single(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $category = CategoriesRepositories::single($requestData['id']);
            if (!$category instanceof Categories) {
                return response()->json(['status' => 500, 'message' => 'Cannot find category'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Category_view);
            return response()->json(['status' => 200, 'data' => $category], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $category = Categories::find($requestData['id']);
            if (!$category instanceof Categories) {
                return response()->json(['status' => 500, 'message' => 'Cannot find category'], 200);
            }
            if (!$category->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete category'], 200);
            }
            Helpers::relationalDataAction($category->id, 'category_id', 'delete', new Invoices());
            Helpers::fileRemove($category, 'icon');
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Category_delete);
            return response()->json(['status' => 200, 'message' => 'Category deleted successfully '], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $filter = [
                'keyword' => $requestData['keyword'] ?? '',
                'list_type' => $requestData['list_type'] ?? 'active',
            ];
            $paginatedData = [
                'limit' => $requestData['limit'] ?? 15,
                'order_by' => $requestData['order_by'] ?? 'id',
                'order_mode' => $requestData['order_mode'] ?? 'DESC',
            ];
            $user = $requestData['session_user'];
            $result = CategoriesRepositories::list($filter, $paginatedData, $user);
            return response()->json(['status' => 200, 'data' => $result]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function archiveOrRestore(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $category = Categories::find($requestData['id']);
            if (!$category instanceof Categories) {
                return response()->json(['status' => 500, 'message' => 'Cannot find category'], 200);
            }
            $category->is_active = $category->is_active == 0 ? 1 : 0;
            if (!$category->save()) {
                $message = 'Cannot restore category';
                if ($category->is_active == 0) {
                    $message = 'Cannot archive category';
                }
                return response()->json(['status' => 500, 'message' => $message], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],$category->is_active == 1 ? UserLogType::Category_restore : UserLogType::Category_archive);
            $message = 'Category archive successfully';
            Helpers::relationalDataAction($category->id, 'category_id', 'archive', new Invoices());
            if ($category->is_active == 1) {
                Helpers::relationalDataAction($category->id, 'category_id', 'restore', new Invoices());
                $message = 'Category restore successfully';
            }
            return response()->json(['status' => 200, 'message' => $message], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
