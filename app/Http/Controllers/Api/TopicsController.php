<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Http\Resources\TopicResource;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Queries\TopicQuery;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

use App\Models\User;

class TopicsController extends Controller
{

    public function index(Request $request, TopicQuery $query)
    {
//        $query = $topic->query();
//        if ($categoryId = $request->category_id) {
//            $query->where('category_id', $categoryId);
//        }
//        $topics = $query
//            ->with("user","category")
//            ->withOrder($request->order)->paginate();

//        $topics = QueryBuilder::for(Topic::class)
//            ->allowedIncludes('user', 'category')
//            ->allowedFilters([
//                'title',
//                AllowedFilter::exact('category_id'),
//                AllowedFilter::scope('withOrder')->default('recentReplied'),
//            ])
//            ->paginate();
//        return TopicResource::collection($topics);

        $topics = $query->paginate();
        return TopicResource::collection($topics);

    }
    public function show($topicId,TopicQuery $query)
    {
       // return new TopicResource($topic);

//        $topic = QueryBuilder::for(Topic::class)
//            ->allowedIncludes('user', 'category')
//            ->findOrFail($topicId);
//
//        return new TopicResource($topic);

        $topic = $query->findOrFail($topicId);
        return new TopicResource($topic);
    }
    //
    public function store(TopicRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);//验证修改权限
        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return response(null, 204);
    }

    public function userIndex(Request $request, User $user,TopicQuery $query)
    {
//        $query = $user->topics()->getQuery();
//
//        $topics = QueryBuilder::for($query)
//            ->allowedIncludes('user', 'category')
//            ->allowedFilters([
//                'title',
//                AllowedFilter::exact('category_id'),
//                AllowedFilter::scope('withOrder')->default('recentReplied'),
//            ])
//            ->paginate();
//
//        return TopicResource::collection($topics);

        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);

    }
}
