<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::latest()->paginate(10);
        return view('admin.tags.index' , compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20|unique:App\Models\Tag,name'
        ]);

        Tag::create([
            'name' => $request->name
        ]);

        toastr()->success($request->name . ' ' . 'با موفقیت به تگ ها اضافه شد');
        return redirect()->route('admin.tags.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return view('admin.tags.show' , compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit' , compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required','min:3','max:20',Rule::unique('tags')->ignore($id)]
        ]);

        $tag->update([
            'name' => $request->name
        ]);

        toastr()->success('با موفقیت تگ ویرایش شد');
        return redirect()->route('admin.tags.index');
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $tags = Tag::where('name', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.tags.index' , compact('tags'));
        }else{
            $tags = Tag::latest()->paginate(10);
            return view('admin.tags.index' , compact('tags'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Tag::destroy($request->tag);

        toastr()->success('تگ مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
