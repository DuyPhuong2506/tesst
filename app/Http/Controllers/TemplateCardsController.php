<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateWeddingTemplateCardRequest;
use App\Services\TemplateCardService;
use DB;

class TemplateCardsController extends Controller
{

    protected $templateCardService;

    public function __construct(TemplateCardService $templateCardService)
    {
        $this->templateCardService = $templateCardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->templateCardService->getTemplateCards();
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.user.update_fail')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateWeddingTemplateCardRequest $request)
    {  
        $requestData = $request->only('name', 'type');
        $file = $request->file('card_path');
        DB::beginTransaction();
        try {
            $data = $this->templateCardService
                         ->createTemplateCard($requestData, $file);
            if($data){
                DB::commit();
                return $this->respondSuccess($data);
            }
            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.user.update_fail')
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
