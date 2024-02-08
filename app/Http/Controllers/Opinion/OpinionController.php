<?php

namespace App\Http\Controllers\Opinion;

use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OpinionController extends Controller
{
    use ApiResponseHelper;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $id)
    {
        $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);
        $opinions = Opinion::select('id', 'content', 'rate', 'category_id', 'user_id')->paginate($per_page, ['*'], 'page', $pageNumber);
        return $this->setCode(200)->setMessage('Successe')->setData($opinions->items())->send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $validator = Validator::make($request->all(), [
            'content'=> 'required|string',
            'rate'=> [
                'nullable',
                Rule::in([0, 1, 2, 3, 4, 5])
            ],
            'category_id'=>'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false ,'message' => collect($validator->errors())->flatten(1)], 422);
        }
        Opinion::create([
            'content'=>$request->content,
            'rate'=> $request->rate,
            'category_id'=> $request->category_id,
            'user_id'=> $user_id
        ]);
        return $this->setCode(200)->setMessage('Successe')->send();
    }

    /**
     * Display the specified resource.
     */

    public function show_opinions_per_category (Request $request )
    {
      $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);
        if($request ->has("category_id")){
            $opinions = Opinion::select('id','content', 'rate' ,'user_id',  'category_id')->where('category_id',$request->category_id)->with('user','category')->paginate($per_page, ['*'], 'page', $pageNumber);
        }
        else{
            $opinions = Opinion::select('id', 'content', 'rate', 'category_id', 'user_id')->with('user','category')->paginate($per_page, ['*'], 'page', $pageNumber);
        }
        return $this->setCode(200)->setMessage('Successe')->setData($opinions->items())->send();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizCheck('حذف الآراء');
        Opinion::findOrFail($id)->delete();

        return $this->setCode(200)->setMessage( 'The Opinion successfully Deleted')->send();

    }
}
