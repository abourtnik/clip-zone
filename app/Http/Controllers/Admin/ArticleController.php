<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\Export;
use App\Models\Article;
use Illuminate\Contracts\View\View;
use \Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ArticleController
{
    public function index () : View {
        return view('admin.articles.index', [
            'articles' => Article::paginate(15)
        ]);
    }

    public function export (): RedirectResponse {
        Export::dispatch(Article::class, Auth::user());
        return redirect()->route('admin.articles.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
