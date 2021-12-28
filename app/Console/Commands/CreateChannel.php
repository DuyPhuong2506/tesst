<?php

namespace App\Console\Commands;

use App\Constants\Common;
use Illuminate\Console\Command;
use App\Constants\CustomerConstant;
use App\Constants\EventConstant;
use App\Constants\Role;
use App\Libs\Agora\RtcTokenBuilder;

class CreateChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CreateChannel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create wedding online guest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now()->startOfDay();
        $weddings = \DB::table('weddings')
            ->join('customers', 'weddings.id', '=', 'customers.wedding_id')
            // ->where('guest_invitation_response_date', '>=', $now)
            ->select('weddings.id','weddings.guest_invitation_response_date', 'weddings.place_id')
            ->groupBy('weddings.id')
            ->get();
        foreach($weddings as $wedding) {
            $roleHost = RtcTokenBuilder::RolePublisher;
            $roleMember = RtcTokenBuilder::RoleSubscriber;
           
            $tables = \DB::table('table_positions')
                ->join('customer_table', 'customer_table.table_position_id', '=', 'table_positions.id')
                ->join('customers', 'customers.id', '=', 'customer_table.customer_id')
                ->where('table_positions.place_id', $wedding->place_id)
                ->select('table_positions.*')
                ->groupBy('table_positions.id')
                ->get();
                

            // inster guest 
            $this->createChannelCoupe($wedding->id);
            $this->createChannelStage($wedding->id);
            $this->createChannelTest($wedding->id);

            foreach($tables as $key => $table) {
                $tableAccount = \DB::table('customers')
                    ->join('customer_table', 'customers.id', '=', 'customer_table.customer_id')
                    ->select('customers.*', 'customer_table.table_position_id as table_position_id')
                    ->where('table_position_id', $table->id)
                    ->where('wedding_id', $wedding->id)
                    ->where('role', Role::NORMAL_TABLE)
                    ->first();

                $checkChannel = \DB::table('channels')
                    ->where('name', 'like', $table->position)
                    ->where('wedding_id', $wedding->id)
                    ->exists();
                if(!$checkChannel){

                    $channel = [
                        'wedding_id'    => $wedding->id,
                        'name'          => $table->position,
                        'display_name'  => $table->position,
                        'amount'        => 6,
                        'status'        => Common::STATUS_FALSE,
                        'type'          => EventConstant::TYPE_GUEST,
                        'start_time'    => null,
                        'end_time'      => null,
                        'created_at'    => \Carbon\Carbon::now(),
                        'updated_at'    => \Carbon\Carbon::now(),
                        'role'          => RtcTokenBuilder::RolePublisher,
                        'customer_id'   => $tableAccount->id ?? null,
                        'table_position_id'   => $table->id ?? null,
                    ];

                    $id = \DB::table('channels')->insertGetId($channel);

                    // if($id) {
                    //     $customers = \DB::table('customers')
                    //         ->where('wedding_id', $wedding->id)
                    //         ->where('table_position_id', $table->id)
                    //         ->where('role', Role::GUEST)
                    //         ->get();
                        
                    //     $customer_join_channels = [];
                    //     foreach($customers as $customer) {
                    //         $customer_channel = [
                    //             'channel_id'    => $id,
                    //             'is_host'       => Common::STATUS_TRUE,
                    //             'is_guest'      => Common::STATUS_FALSE,
                    //             'customer_id'   => $customer->id,
                    //             'status'        => Common::STATUS_TRUE,
                    //         ];

                    //         array_push($customer_join_channels, $customer_channel);
                    //     }

                    //     \DB::table('customer_channel')->insert($customer_join_channels);
                    // }
                }
            }
        }
    }

    public function getRoomGuest($customers)
    {
        $countDevices = count($customers);
        $limit = 6;
        $rooms = [];
        $max = ceil($countDevices / $limit); 
        $stringRoomGuest = 'guest_';
        for ($i = 1; $i <= $max; $i++) {
            $length = $i - 1;
            $room = ['id' => 1, 'username' => $stringRoomGuest . random_str(6) . getDateStringRandom(), 'role' => \App\Libs\Agora\RtcTokenBuilder::RolePublisher];
            array_push($rooms, $room);
        }

        return $rooms;
    }

    public function getDefaultRoomWedding($name, $role, $wedding_id, $customer_id = null)
    {
        $data = [
            'wedding_id'    => $wedding_id,
            'name'          => $name,
            'display_name'  => $name,
            'amount'        => 6,
            'status'        => Common::STATUS_FALSE,
            'type'          => $role,
            'start_time'    => null,
            'end_time'      => null,
            'created_at'    => \Carbon\Carbon::now(),
            'updated_at'    => \Carbon\Carbon::now(),
            'role'          => RtcTokenBuilder::RolePublisher,
            'customer_id'   => $customer_id,
            'table_position_id' => null
        ];

        return $data;
    }

    public function createChannelCoupe($wedding_id)
    {
        $checkChannel = \DB::table('channels')
            ->where('name', 'like', 'coupe_'.'%')
            ->where('wedding_id', $wedding_id)
            ->exists();
        if(!$checkChannel){
            $customers = \DB::table('customers')
                ->where('wedding_id', $wedding_id)
                ->whereIn('role', [Role::GROOM, Role::BRIDE])
                ->where('join_status', CustomerConstant::JOIN_STATUS_APPROVED)
                ->get();
            $tableAccount = \DB::table('customers')
                ->where('wedding_id', $wedding_id)
                ->where('role', Role::COUPE_TABLE)
                ->first();
            $tableAccountId = $tableAccount->id ?? null;
            $channel = $this->getDefaultRoomWedding('coupe_'. random_str(6) . getDateStringRandom(), EventConstant::TYPE_COUPE, $wedding_id, $tableAccountId);
            $id = \DB::table('channels')->insertGetId($channel);

            // foreach($customers as $customer) {
            //     $customer_channel = [
            //         'channel_id'    => $id,
            //         'is_host'       => Common::STATUS_TRUE,
            //         'is_guest'      => Common::STATUS_FALSE,
            //         'customer_id'   => $customer->id,
            //         'status'        => Common::STATUS_TRUE,
            //     ];

            //     \DB::table('customer_channel')->insert($customer_channel);
            // }
        }
        
    }

    public function createChannelStage($wedding_id)
    {
        $checkChannel = \DB::table('channels')
            ->where('name', 'like', 'stage_'.'%')
            ->where('wedding_id', $wedding_id)
            ->exists();
        if(!$checkChannel){
            $customers = \DB::table('customers')
                ->where('wedding_id', $wedding_id)
                ->whereIn('role', [Role::GROOM, Role::BRIDE, Role::GUEST])
                ->where('join_status', CustomerConstant::JOIN_STATUS_APPROVED)
                ->get();
            $tableAccount = \DB::table('customers')
                ->where('wedding_id', $wedding_id)
                ->where('role', Role::STAGE_TABLE)
                ->first();
            $tableAccountId = $tableAccount->id ?? null;
            $channel = $this->getDefaultRoomWedding('stage_'. random_str(6) . getDateStringRandom(), EventConstant::TYPE_STAGE, $wedding_id, $tableAccountId);
            $id = \DB::table('channels')->insertGetId($channel);

            // foreach($customers as $customer) {
            //     $customer_channel = [
            //         'channel_id'    => $id,
            //         'is_host'       => Common::STATUS_TRUE,
            //         'is_guest'      => Common::STATUS_FALSE,
            //         'customer_id'   => $customer->id,
            //         'status'        => Common::STATUS_TRUE,
            //     ];

            //     \DB::table('customer_channel')->insert($customer_channel);
            // }
        }
    }

    public function createChannelTest($wedding_id)
    {
        $checkChannel = \DB::table('channels')
            ->where('name', 'like', 'test_'.'%')
            ->where('wedding_id', $wedding_id)
            ->exists();
        if(!$checkChannel){
            $customers = \DB::table('customers')
                // ->join('customer_tasks', 'customers.id', '=', 'customer_tasks.customer_id')
                // ->where('customer_tasks.name',  CustomerConstant::CUSTOMER_TASK_SPEECH)
                ->where('customers.wedding_id', $wedding_id)
                ->whereIn('customers.role', [Role::GUEST])
                ->where('customers.join_status', CustomerConstant::JOIN_STATUS_APPROVED)
                ->get();
            $tableAccount = \DB::table('customers')
                ->where('wedding_id', $wedding_id)
                ->where('role', Role::SPEECH_TABLE)
                ->first();
            $tableAccountId = $tableAccount->id ?? null;
            $channel = $this->getDefaultRoomWedding('test_'. random_str(6) . getDateStringRandom(), EventConstant::TYPE_TEST, $wedding_id, $tableAccountId);
            $id = \DB::table('channels')->insertGetId($channel);

            // foreach($customers as $customer) {
            //     $customer_channel = [
            //         'channel_id'    => $id,
            //         'is_host'       => Common::STATUS_TRUE,
            //         'is_guest'      => Common::STATUS_FALSE,
            //         'customer_id'   => $customer->id,
            //         'status'        => Common::STATUS_TRUE,
            //     ];

            //     \DB::table('customer_channel')->insert($customer_channel);
            // }
        }
    }
}
