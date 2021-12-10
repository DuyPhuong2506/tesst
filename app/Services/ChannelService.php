<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use App\Models\Restaurant;
use App\Repositories\ChannelRepository;
use Mail;
use Hash;
use Str;
use Carbon\Carbon;
use App\Constants\Common;

class ChannelService
{
    protected $channelRepo;

    public function __construct(ChannelRepository $channelRepo)
    {
        $this->channelRepo = $channelRepo;
    }

    public function showDetail($id)
    {   
        $place = $this->channelRepo->model->whereId($id)->first();

        return $place;
    }

    public function getAll($request)
    {   
        $orderBy = isset($request['order_by']) ? explode('|', $request['order_by']) : [];
        $keyword = !empty($request['keyword']) ? $request['keyword'] : null;
        $paginate = !empty($request['paginate']) ? $request['paginate'] : Common::PAGINATE;
        $list = $this->channelRepo->model
            ->whereWeddingId(auth()->guard('customer')->user()->wedding_id)
            ->when(!empty($keyword), function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            })
            ->where('status', Common::STATUS_TRUE)
            ->orderBy('created_at', 'desc');

        if($paginate != Common::PAGINATE_ALL){
            $list = $list->paginate($paginate);
        } else {
            $list = $list->get();
        }
        return $list;
    }
}
