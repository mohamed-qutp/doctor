<?php

namespace App\Http\Controllers\Banner;

use App\Models\Banner;
use App\Helpers\UploadImage;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\BannerUpdateRequest;

class BannerController extends Controller
{
    use ApiResponseHelper;
    use UploadImage;
    use AuthorizeCheck;


    public function index()
    {
        $banners = Banner::all();
        return $this->setCode(200)->setMessage('Successe')->setData($banners)->send();
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(BannerStoreRequest $request)
    // {
    //     $name =$this->UploadImage($request, 'Banner_Image');
    //     Banner::create([
    //         'img'=> env('APP_URL').$name  ,
    //     ]);
    //     return $this->setCode(200)->setMessage('Panner Posted successfully')->send();
    // }
    /**
     * Update the specified resource in storage.
     */
    public function update(BannerUpdateRequest $request, string $id)
    {
        $this->authorizCheck('تعديل الاعلانات');
        $banner = Banner::find($id);
        $name =$this->UploadImage($request, 'Banner_Image', $banner->img);
        $banner ->update([
            'img'=> $name==null ? $banner->img : env('APP_URL').$name,
        ]);
        return $this->setCode(200)->setMessage('Panner Updated successfully')->send();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);

        if (File::exists(str_replace(env('APP_URL'), "", $banner->img))) {
            File::delete(str_replace(env('APP_URL'), "", $banner->img));
        }
        Banner::find($id)->delete();

        return $this->setCode(200)->setMessage('Panner Deleted successfully')->send();


    }
}
