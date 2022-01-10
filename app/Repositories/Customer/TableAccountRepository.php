<?php

namespace App\Repositories\Customer;

use App\Constants\Role;
use App\Repositories\BaseRepository;

class TableAccountRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Customer::class;
    }
    
    /**
     * Get list table account of wedding
     */
    public function getListOfWedding(string $weddingId)
    {
        $data = $this->model
                ->where('role', Role::NORMAL_TABLE)
                ->whereHas('wedding', function($q) use ($weddingId) {
                    $q->where('id', $weddingId);
                })
                ->get();
        return $data;
    }
} 