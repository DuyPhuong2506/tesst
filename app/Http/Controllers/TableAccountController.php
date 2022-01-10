<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TableAccountService;

class TableAccountController extends Controller
{
    protected $tableAccountService;

    public function __construct(TableAccountService $tableAccountService)
    {
        $this->tableAccountService = $tableAccountService;
    }
    
    /**
     * Get list table account with wedding 
     */

    public function getTableAccountOfWedding($weddingId)
    {
        $data = $this->tableAccountService->getListOfWedding($weddingId);
        return $this->respondSuccess($data);
    }
}
