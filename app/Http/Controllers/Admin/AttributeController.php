<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::latest()->paginate(10);
        return view('admin.attributes.index' , compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20|unique:App\Models\Attribute,name'
        ]);

        Attribute::create([
            'name' => $request->name
        ]);

        toastr()->success($request->name . '' . ' با موفقیت به ویژگی ها اضافه شد');
        return redirect()->route('admin.attributes.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        return view('admin.attributes.show' , compact('attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit' , compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required','min:3','max:20',Rule::unique('attributes')->ignore($id)],
        ]);

        $attribute->update([
            'name' => $request->name,
        ]);

        toastr()->success('با موفقیت ویژگی ویرایش شد');
        return redirect()->route('admin.attributes.index');
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $attributes = Attribute::where('name', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.attributes.index' , compact('attributes'));
        }else{
            $attributes = Attribute::latest()->paginate(10);
            return view('admin.attributes.index' , compact('attributes'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Attribute::destroy($request->attribute);

        toastr()->success('ویژگی مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
