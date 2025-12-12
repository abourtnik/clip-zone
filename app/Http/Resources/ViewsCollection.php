<?php

namespace App\Http\Resources;

use App\Http\Resources\Video\VideoListResource;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ViewsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function (array $item) {
                return [
                    'date'  => $item['date'],
                    'views' => $item['views']->map(function (View $view) {
                        return [
                            'id' => $view->id,
                            'video' => new VideoListResource($view->video),
                        ];
                    })
                ];
            })->toArray(),
        ];
    }
}
