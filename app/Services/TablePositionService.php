<?php
namespace App\Services;

use App\Constants\Common;
use App\Repositories\TablePositionRepository;

class TablePositionService
{

    protected $tablePositionRepo;

    public function __construct(TablePositionRepository $tablePositionRepo)
    {
        $this->tablePositionRepo = $tablePositionRepo;
    }

    public function getListTable()
    {
        return $this->tablePositionRepo->model
                    ->where('status', Common::STATUS_TRUE)
                    ->select('id', 'position')
                    ->get();
    }

}