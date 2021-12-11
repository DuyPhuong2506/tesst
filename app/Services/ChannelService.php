<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use App\Models\Restaurant;
use App\Repositories\ChannelRepository;
use Illuminate\Support\Facades\Auth;
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
        $channel = $this->channelRepo->model->whereId($id)->first();

        return $channel;
    }

    public function getAll($request)
    {   
        $orderBy = isset($request['order_by']) ? explode('|', $request['order_by']) : [];
        $keyword = !empty($request['keyword']) ? $request['keyword'] : null;
        $paginate = !empty($request['paginate']) ? $request['paginate'] : Common::PAGINATE;
        $list = $this->channelRepo->model;
        if(Auth::guard('customer')->check()){
            $list = $list->whereWeddingId(auth()->guard('customer')->user()->wedding_id)
                ->when(!empty($keyword), function($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->where('status', Common::STATUS_TRUE)
                ->orderBy('created_at', 'desc');
        } else if(Auth::guard('table_account')->check()){
            $auth = Auth::guard('table_account')->user();
            $list = $list->whereHas('wedding', function($q) use ($auth) {
                    $q->where('place_id', $auth->place_id)
                        ->where('is_close', Common::STATUS_FALSE);
                })
                ->when(!empty($keyword), function($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->where('status', Common::STATUS_TRUE)
                ->orderBy('created_at', 'desc');
        }

        if($paginate != Common::PAGINATE_ALL){
            $list = $list->paginate($paginate);
        } else {
            $list = $list->get();
        }
        return $list;
    }

    public function updateChannel(int $id, array $request)
    {   
        $channel = $this->showDetail($id);
        $data =  [];
        if($channel){
            if(is_numeric($request['status'])){
                $data['status'] = $request['status'];
            }
            $channel->update($data);
        }
        return $channel;
    }
}
