<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class CategoryController extends Controller
{
  public function index(): Response
  {
    $categories = Category::orderBy('is_pinned', 'desc')
      ->where('user_id', Auth::user()->id)
      ->orderBy('name', 'asc')
      ->paginate(16);
    return inertia('Category/Index', compact('categories'));
  }

  public function store(CategoryStoreRequest $request): RedirectResponse
  {
    try {
      $category = Category::create($request->data());
      return back()->with('success', "Category {$category->name} created successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
  {
    try {
      $category->update($request->data());
      return back()->with('success', "Category {$category->name} updated successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function destroy(Category $category): RedirectResponse
  {
    try {
      $category->delete();
      return back()->with("success", "Category {$category->name} deleted successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }
}
