<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\WeddingCardService;
use App\Http\Requests\CreateWeddingCardRequest;
use App\Http\Requests\UploadCouplePhotoRequest;
use DB;
use Auth;

class WeddingCardsController extends Controller
{

    protected $weddingCardService;

    public function __construct(WeddingCardService $weddingCardService)
    {
        $this->weddingCardService = $weddingCardService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
    public function store(CreateWeddingCardRequest $request)
    {
        $weddingCard = $request->only(
            'template_card_id', 'couple_photo', 
            'content', 'wedding_price'
        );
        $weddingId = Auth::guard('customer')->user()->wedding_id;
        $bankAccount = $request->bank_accounts;
        $data = $this->weddingCardService
                     ->createWeddingCard($weddingCard, $bankAccount, $weddingId);

        DB::beginTransaction();
        try {
            if($data){
                DB::commit();
                return $this->respondSuccess($data);
            }
            
            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.wedding_card.create_fail')
            );
        }
    }

    public function getPreSigned(UploadCouplePhotoRequest $request)
    {
        try {
            $data = $this->weddingCardService->getPreSigned($request);

            return $this->respondSuccess($data);
        }  catch (\Exception $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
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
