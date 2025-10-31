<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\CategoryAttribute;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories-index', ['only' => ['show', 'index']]);
        $this->middleware('permission:categories-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:categories-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Category::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $categories = $query->latest()->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View|Application|Factory
    {
        $parentCategories = Cache::remember('parent_categories', now()->addHour(), function () {
            return Category::whereNull('parent_id')->pluck('title', 'id');
        });

        $attributes = Cache::remember('attributes', now()->addHour(), function () {
            return Attribute::pluck('title', 'id');
        });

        return view('admin.categories.create', compact('parentCategories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category = Category::create([
                'title' => $request->input('title'),
                'parent_id' => $request->integer('parent_id'),
                'is_active' => true,
                'description' => $request->input('description'),
                'icon' => $request->input('icon'),
                'priority' => $request->priority ?? 0,
            ]);

            CategoryAttribute::create([
                'category_id' => $category->id,
                'attribute_id' => $request->input('variation_attribute_id'),
                'type' => 'variation',
            ]);

            foreach ($request->input('filter_attribute_ids') as $attribute_id) {
                CategoryAttribute::create([
                    'category_id' => $category->id,
                    'attribute_id' => $attribute_id,
                    'type' => 'filter',
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.category.create_failed'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.category.created'));
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View|Application|Factory
    {
        $category->load(['variation', 'filters']);
        $parentCategories = Cache::remember('parent_categories', now()->addHour(), function () {
            return Category::whereNull('parent_id')->pluck('title', 'id');
        });

        $attributes = Cache::remember('attributes', now()->addHour(), function () {
            return Attribute::pluck('title', 'id');
        });
        $relatedAttributes = $this->getCategoryAttribute($category);

        return view('admin.categories.edit', compact('category', 'parentCategories', 'attributes', 'relatedAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category->update([
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
                'is_active' => $request->input('is_active'),
                'description' => $request->input('description'),
                'icon' => $request->input('icon'),
            ]);

            $category->attributes()->detach();

            CategoryAttribute::create([
                'category_id' => $category->id,
                'attribute_id' => $request->input('variation_attribute_id'),
                'type' => 'variation',
            ]);

            foreach ($request->input('filter_attribute_ids') as $attribute_id) {
                CategoryAttribute::create([
                    'category_id' => $category->id,
                    'attribute_id' => $attribute_id,
                    'type' => 'filter',
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.category.update_failed'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.category.updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            if ($category->children()->count() > 0 && $category->products()->count() > 0) {
                flash()->warning('دسته بندی غیرقابل حذف است!');
                return redirect()->back();
            }

            $category->delete();

            flash()->success(config('flasher.category.deleted'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.category.delete_failed'));
            return redirect()->back();
        }
    }

    public function getCategoryAttribute(Category $category): array
    {
        $filters = $category->filters()->pluck('title', 'id');
        $variation = $category->variation()->take(1)->pluck('title', 'id');

        return ['filters' => $filters, 'variation' => $variation];
    }
}
