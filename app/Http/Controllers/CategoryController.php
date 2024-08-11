<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
use App\Models\Categories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function create(CreateCategoryRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'You are not an admin'], 403);
        }

        $data = $request->validated();
        $category = Categories::create($data);

        return response()->json(new CategoryResource($category), 201);
    }

    public function get($id): JsonResponse
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(new CategoryResource($category), 200);
    }

    public function getAll(): JsonResponse
    {
        $categories = Categories::all();
        return response()->json(CategoryResource::collection($categories), 200);
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'You are not an admin'], 403);
        }

        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $data = $request->validated();
        $category->update($data);

        return response()->json(new CategoryResource($category), 200);
    }

    public function delete($id): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'You are not an admin'], 403);
        }

        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
