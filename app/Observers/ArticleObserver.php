<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Laravel\Nova\Nova;
use Laravel\Nova\Http\Requests\NovaRequest;
class ArticleObserver
{

  public function saving(Article $article)
  {
    Nova::whenServing(fn (NovaRequest $request) =>
      $article->url = $this->getUrl($article)
    );
  }

  protected function getUrl(Article $article)
  {
    if ($article->article_category_id) {
      $category = ArticleCategory::find($article->article_category_id);
      return "{$category->url}/{$article->slug}";
    } else {
      return $article->slug;
    }
  }
}
