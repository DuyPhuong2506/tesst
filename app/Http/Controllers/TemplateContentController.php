<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\TemplateContentService;
use App\Http\Requests\CreateTemplateContentRequest;
use DB;

class TemplateContentController extends Controller
{

    protected $templateContentService;

    public function __construct(TemplateContentService $templateContentService)
    {
        $this->templateContentService = $templateContentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->templateContentService->getTemplateContents();
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
    public function store(CreateTemplateContentRequest $request)
    {
        $requestData = $request->only('name', 'font_name', 'content', 'status');
        $requestImg  = $request->file('preview_image');

        DB::beginTransaction();
        try {
            $data = $this->templateContentService->createTemplateContent($requestImg, $requestData);
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
