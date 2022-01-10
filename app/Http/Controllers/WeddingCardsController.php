<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\WeddingCardService;
use App\Http\Requests\CreateWeddingCardRequest;
use App\Http\Requests\UploadCouplePhotoRequest;
use App\Http\Requests\UpdateCardContentRequest;
use App\Http\Requests\StaffGetGuestWeddingCardRequest;
use DB;
use Auth;

class WeddingCardsController extends Controller
{

    protected $weddingCardService;
    protected $customer;

    public function __construct(WeddingCardService $weddingCardService)
    {
        $this->weddingCardService = $weddingCardService;
        $this->customer = Auth::guard('customer')->user();
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
        $weddingCard = $request->only('template_card_id', 'couple_photo');
        $weddingId = $this->customer->wedding_id;
        $data = $this->weddingCardService
                     ->createWeddingCard($weddingCard, $weddingId);

        if($data){
            return $this->respondSuccess([
                'message' => __('messages.wedding_card.create_success')
            ]);
        }
        
        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.wedding_card.create_fail')
        );
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
    public function show()
    {   
        $weddingId = $this->customer->wedding_id;
        $data = $this->weddingCardService->showWeddingCard($weddingId);
        if($data){
            return $this->respondSuccess($data);
        }
        
        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.wedding_card.detail_fail')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $weddingCardID
     * @return \Illuminate\Http\Response
     */
    public function staffGetWeddingCard(StaffGetGuestWeddingCardRequest $request)
    {   
        $guestID = $request->id;
        $data = $this->weddingCardService->staffGetWeddingCard($guestID);
        if($data){
            return $this->respondSuccess($data);
        }
        
        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.wedding_card.detail_fail')
        );
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
    public function update(UpdateCardContentRequest $request)
    {
        $cardContent = $request->only('content');
        $weddingId = $this->customer->wedding_id;
        $data = $this->weddingCardService->updateCardContent($cardContent, $weddingId);
        
        if($data){
            return $this->respondSuccess($data);
        }
        
        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.wedding_card.update_fail')
        );
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

    public function notifyToStaff()
    {
        $weddingID = $this->customer->wedding_id;
        $status = $this->weddingCardService->notifyToStaff($weddingID);

        try {
            if($status){
                return $this->respondSuccess([
                    'message' => __('messages.wedding_card.send_mail_sucess')
                ]);
            }

            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.wedding_card.send_mail_fail')
            );

        } catch (\Throwable $th) {
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.wedding_card.send_mail_fail')
            );
        }   
    }
}
