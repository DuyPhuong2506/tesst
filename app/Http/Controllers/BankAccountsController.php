<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Services\BankAccountService;
use Auth;
use DB;

class BankAccountsController extends Controller
{

    protected $bankAccountService;
    protected $customer;

    public function __construct(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
        $this->customer = Auth::guard('customer')->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(UpdateBankAccountRequest $request, $id)
    {
        $bankAccount = $request->bank_accounts;
        $weddingPrice = $request->only('wedding_price');
        $weddingId = $this->customer->wedding_id;

        DB::beginTransaction();
        try {
            $data = $this->bankAccountService->updateOrCreateBankAccount(
                $bankAccount, 
                $weddingPrice,
                $weddingId
            );

            if($data){
                DB::commit();
                return $this->respondSuccess($data);
            }

            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondError(
                Response::HTTP_BAD_REQUEST, __('messages.wedding_card.update_fail')
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
        //
    }
}
