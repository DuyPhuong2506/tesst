<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateParticipantRequest;
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
        //
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
                return $this->respondSuccess($data);
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
