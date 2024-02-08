<?php

namespace App\Http\Controllers\Consultatoins;

use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultationsStoreRequest;

class ConsultationController extends Controller
{
    use ApiResponseHelper;
    use AuthorizeCheck;

    public function index ()
    {
        $this->authorizCheck('المشاهدة فقط');
        $all_count = Consultation::all()->count();
        $accept_count = Consultation::where('statue',1)->count();
        $reject_count = Consultation::where('statue',0)->count();
        return $this->setCode(200)->setMessage('Successe')->setData(['all_count'=>$all_count, 'accept_count'=>$accept_count,'reject_count' =>$reject_count ])->send();

    }
    public function store(ConsultationsStoreRequest $request)
    {
        $consultations = Consultation::create([
            'statue' => $request->statue
        ]);
        return $this->setCode(200)->setMessage('Successe')->setData($consultations)->send();
    }

}