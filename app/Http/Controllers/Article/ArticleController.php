<?php

namespace App\Http\Controllers\Article;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Helpers\UploadImage;
use Illuminate\Http\Request;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use App\Helpers\TopicNotificationHelper;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;




class ArticleController extends Controller
{
    use ApiResponseHelper;
    use UploadImage;
    use AuthorizeCheck;
    use TopicNotificationHelper;
    protected $notification;
    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }

    /**
     * Display a listing of the resource.
     */
    public function searchAll(Request $request)
    {
        $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);

        if($request->has('category_id'))
        {
            if($request->has('keyword'))
            {
                $keyword = $request->keyword ;
                $articles = Article::orderby('id', 'DESC')->with('category')
                    ->select('id','title_ar','title_en', 'description_ar', 'description_en' ,'category_id' , 'img', 'view')
                    ->orWhere([
                        ['category_id',$request->category_id],
                        ['title_ar', 'like', "%$keyword%"],
                        ])
                    ->orWhere([
                        ['category_id',$request->category_id],
                        ['title_en', 'like', "%$keyword%"],
                        ])
                    ->orWhere([
                        ['category_id',$request->category_id],
                        ['description_en', 'like', "%$keyword%"],
                        ])
                    ->orWhere([
                        ['category_id',$request->category_id],
                        ['description_ar', 'like', "%$keyword%"],
                        ])
                    ->paginate($per_page, ['*'], 'page', $pageNumber);
            }else{
                $articles = Article::orderby('id', 'DESC')->with('category')
                ->whereNotNull('title_ar')
                ->whereNotNull('title_en')
                ->whereNotNull('description_en')
                ->whereNotNull('description_ar')
                ->select('id','title_ar','title_en', 'description_ar', 'description_en' ,'category_id' , 'img', 'view')
                ->Where('category_id',$request->category_id)
                ->paginate($per_page, ['*'], 'page', $pageNumber);
            }
        }
        else
        {
            if($request->has('keyword'))
            {
                $keyword = $request->keyword ;
                $articles = Article::orderby('id', 'DESC')->with('category')
                    ->select('id','title_ar','title_en', 'description_ar', 'description_en' ,'category_id' , 'img', 'view')
                    ->orWhere('title_en','like',"%$keyword%")
                    ->orWhere('title_ar', 'like', "%$keyword%")
                    ->orWhere('description_en','like',"%$keyword%")
                    ->orWhere('description_ar','like',"%$keyword%")
                    ->paginate($per_page, ['*'], 'page', $pageNumber);
            }else{
                $articles = Article::orderby('id', 'DESC')->with('category')
                ->whereNotNull('title_ar')
                ->whereNotNull('title_en')
                ->whereNotNull('description_en')
                ->whereNotNull('description_ar')
                ->select('id','title_ar','title_en', 'description_ar', 'description_en' ,'category_id' , 'img', 'view')
                ->paginate($per_page, ['*'], 'page', $pageNumber);
            }
        }
        return $this->setCode(200)->setMessage('Successe')->setData($articles->items())->send();
    }

    public function store(ArticleStoreRequest $request)
    {
        $this->authorizCheck('انشاء المقالات');

        $name =$this->UploadImage($request, 'Article_images');
        Article::create([
            'title_ar'=> $request->title_ar,
            'title_en'=> $request->title_en,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'category_id' => $request->category_id,
            'img'=> env('APP_URL').$name  ,
        ]);

        $category = Category::select('name_ar','name_en')->where('id', $request->category_id)->first();
        $this->notificationTopic('مقالة جديدة !','New Article !',"تم اضافه مقاله جديده فى قسم $category->name_ar","New article in category $category->name_en");
        return $this->setCode(200)->setMessage('Category Posted successfully')->send();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::select('id','title_' . App::currentLocale() . ' as title' ,'description_'. App::currentLocale() . ' as description' ,'category_id' , 'img', 'view' )->where('id', $id )->first();
        $views = (int) $article->view ++ ;
        $article->update([
            'view' => $views ,
        ]);
        return $this->setCode(200)->setMessage('Successe')->setData($article)->send();
    }
    public function showArticlesPercategory (Request $request)
    {
        $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);
        if($request ->has("category_id")){
            $articles = Article::select('id','title_' . App::currentLocale() . ' as title','description_'. App::currentLocale() . ' as description' , 'img' )->where('category_id',$request->category_id)
            ->paginate($per_page, ['*'], 'page', $pageNumber);
        }
        else{
            $articles = Article::select('id','title_' . App::currentLocale() . ' as title' ,'description_'. App::currentLocale() . ' as description' ,'category_id' , 'img'  )
            ->paginate($per_page, ['*'], 'page', $pageNumber);
        }
        return $this->setCode(200)->setMessage('Successe')->setData($articles->items())->send();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $request, string $id)
    {
        $this->authorizCheck('تعديل المقالات');
        $article = Article::where('id', $id)->first();
        $name =$this->UploadImage($request, 'Article_images',$article->img);

            $article->update([
            'title_ar'=> $request->title_ar ?? $article-> title_ar,
            'title_en'=> $request->title_en ?? $article-> title_en,
            'description_en'=> $request->description_en ?? $article-> description_en,
            'description_ar'=> $request->description_ar ?? $article-> description_ar,
            'category_id' => $request->category_id ?? $article-> category_id,
            'img'=> $name==null ? $article->img : env('APP_URL').$name,
        ]);
        return $this->setCode(200)->setMessage('Successe')->setData($article)->send();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizCheck('حذف المقالات');
        $article = Article::where('id', $id)->first();
        if (File::exists(str_replace(env('APP_URL'), "", $article->img))) {
            File::delete(str_replace(env('APP_URL'), "", $article->img));
        }
        Article::find($id)->delete();
        return $this->setCode(200)->setMessage( 'Article Deleted successfully')->send();

    }
}
