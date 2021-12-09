<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TablePositionService;

class TablePositionsController extends Controller
{

    protected $tablePostionService;

    public function __construct(TablePositionService $tablePostionService)
    {
        $this->tablePostionService = $tablePostionService;
    }
    
    public function index()
    {
        return $this->tablePostionService->index();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
