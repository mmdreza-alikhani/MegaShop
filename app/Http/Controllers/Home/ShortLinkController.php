<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;

class ShortLinkController extends Controller
{
    public function __invoke(string $code): RedirectResponse
    {
        $link = ShortLink::where('code', $code)->firstOrFail();
        $link->increment('clicks');

        return match ($link->type) {
            'product' => $this->redirectToProduct($link->target_id),
            'post' => $this->redirectToPost($link->target_id),
            default => abort(404),
        };
    }

    protected function redirectToProduct(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        return redirect()->route('home.products.show', ['product' => $product->slug]);
    }

    protected function redirectToPost(int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        return redirect()->route('home.posts.show', ['post' => $post->slug]);
    }
}
