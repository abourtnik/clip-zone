<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController
{
    public function index () : View {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->orderBy('in_menu', 'desc')
                ->orderBy('position', 'asc')
                ->get(),
            'active_categories' => Category::where('in_menu', true)->get(),
        ]);
    }

    public function organize (Request $request) : RedirectResponse {

        $categories = $request->get('categories');

        foreach ($categories as $index => $id) {
            Category::find($id)->update([
                'position' => $index
            ]);
        }

        return redirect()->route('admin.categories.index');
    }

}
