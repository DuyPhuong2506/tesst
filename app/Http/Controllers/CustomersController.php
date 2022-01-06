<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Http\Requests\StaffGetListGuestRequest;
use App\Http\Requests\CoupleUpdateGuestRequest;
use App\Http\Requests\StaffUpdateGuestRequest;
use App\Http\Requests\CoupleReoderGuestRequest;
use App\Http\Requests\StaffReoderGuestRequest;
use App\Http\Requests\StaffGetGuestRequest;
use App\Services\CustomerService;
use Auth;
use DB;

class CustomersController extends Controller
{
    protected $customerService;
    protected $customer;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->customer = Auth::guard('customer')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $weddingId = $this->customer->wedding_id;
        $requestData = $request->only('paginate');
        $data = $this->customerService->getListParticipant($requestData, $weddingId);

        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.participant.list_fail')
        );
    }

    public function coupleListGuest(Request $request)
    {
        $weddingID = $this->customer->wedding_id;
        $data = $this->customerService->staffCoupleGetListGuest($weddingID, $request);

        if($data){
            return $this->respondSuccess($data); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.list_null'));
    }

    public function staffListGuest(StaffGetListGuestRequest $request)
    {
        $weddingID = $request->id;
        $data = $this->customerService->staffCoupleGetListGuest($weddingID, $request);

        if($data){
            return $this->respondSuccess($data); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.list_null'));
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
     * ==> Couple Create Participant Of The Wedding <==
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateParticipantRequest $request)
    {
        $requestData = $request->all();
        $weddingId = $this->customer->wedding_id;

        DB::beginTransaction();
        try {
            $data = $this->customerService->createParticipant($requestData, $weddingId);

            if($data){
                DB::commit();
                return $this->respondSuccess([
                    'message' => __('messages.participant.create_success')
                ]);
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();
            
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.participant.create_fail')
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
        $weddingId = $this->customer->wedding_id;
        $customerId = $this->customer->id;
        $data = $this->customerService->detailParticipant($id, $weddingId, $customerId);
        
        if($data){
            return $this->respondSuccess($data);
        }else{
            return $this->respondError(
                Response::HTTP_NOT_FOUND, __('messages.participant.not_found')
            );
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.participant.detail_fail')
        );
    }

    public function staffGetGuestInfo(StaffGetGuestRequest $request)
    {
        $guestID = $request->id;
        $data = $this->customerService->staffGetParticipant($guestID);

        if($data){
            return $this->respondSuccess($data);
        }else{
            return $this->respondError(
                Response::HTTP_NOT_FOUND, __('messages.participant.not_found')
            );
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.participant.detail_fail')
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
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParticipantRequest $request)
    {
        $requestData = $request->all();
        $weddingId = $this->customer->wedding_id;

        DB::beginTransaction();
        try {
            $data = $this->customerService->updateParticipantInfo($requestData, $weddingId);
            if($data){
                DB::commit();
                return $this->respondSuccess([
                    'message' => __('messages.participant.update_success')
                ]);
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.participant.update_fail')
            );
        }
    }

    /**
     * UI COUPLE - [U064] Couple Edit Guest Info
     * @param $request 
     * **/
    public function coupleUpdateGuestInfo(CoupleUpdateGuestRequest $request)
    {
        $requestData = $request->all();
        DB::beginTransaction();
        try {
            $status = $this->customerService->coupleUpdateGuestInfo($requestData);
            if($status){
                DB::commit();
                return $this->respondSuccess(
                    ['message' => __('messages.participant.update_success')]
                );
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.participant.update_fail')
            );
        }
    }

    /**
     * UI COUPLE - [AS170] Staff Edit Guest Info
     * @param $request 
     * **/
    public function staffUpdateGuestInfo(StaffUpdateGuestRequest $request)
    {
        $requestData = $request->all();
        DB::beginTransaction();
        try {
            $status = $this->customerService->staffUpdateGuestInfo($requestData);
            if($status){
                DB::commit();
                return $this->respondSuccess(
                    ['message' => __('messages.participant.update_success')]
                );
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.participant.update_fail')
            );
        }
    }

    /**
     * UI COUPLE - [U063.1] reorder
     * @param $request 
     * **/
    public function coupleReoderGuest(CoupleReoderGuestRequest $request)
    {
        $weddingID = $this->customer->wedding_id;
        $requestData = $request->only('id', 'updated_position');
        DB::beginTransaction();
        try {
            $status = $this->customerService->reoderGuest($weddingID, $requestData);
            if($status){
                DB::commit();
                return $this->respondSuccess(
                    ['message' => __('messages.participant.update_success')]
                );
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();
            
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.participant.update_fail')
            );
        }
    }

    /**
     * UI STAFF ADMIN - [AS157] reorder
     * @param $request 
     * **/
    public function staffReoderGuest(StaffReoderGuestRequest $request)
    {
        $weddingID = $request->wedding_id;
        $requestData = $request->only('id', 'updated_position');
        DB::beginTransaction();
        try {
            $status = $this->customerService->reoderGuest($weddingID, $requestData);
            if($status){
                DB::commit();
                return $this->respondSuccess(
                    ['message' => __('messages.participant.update_success')]
                );
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();
            
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.participant.update_fail')
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $weddingId = $this->customer->wedding_id;
        $data = $this->customerService->deleteParticipant($id, $weddingId);
        
        if($data){
            return $this->respondSuccess(
                ['message', __('messages.participant.delete_success')]
            );
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.participant.delete_fail')
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListCustomerInWedding(Request $request)
    {
        $responseData = $this->customerService->getListCustomerInWedding($request->all());

        if($responseData){
            return $this->respondSuccess($responseData);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.customers.request_fail')
        );
    }
}
