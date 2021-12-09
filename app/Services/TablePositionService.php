<?php
namespace App\Services;

use App\Repositories\TablePositionRepository;

class TablePositionService
{

    protected $tablePositionRepo;

    public function __construct(TablePositionRepository $tablePositionRepo)
    {
        $this->tablePositionRepo = $tablePositionRepo;
    }

    public function index()
    {
        return $this->tablePositionRepo->model
                    ->where('status', STATUS_TRUE)
                    ->select('id', 'position')
                    ->get();
    }

}