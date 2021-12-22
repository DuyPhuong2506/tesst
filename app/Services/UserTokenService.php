<?php
namespace App\Services;

use App\Repositories\UserTokenRepository;

class UserTokenService
{
    protected $userTokenRepo;

    public function __construct(UserTokenRepository $userTokenRepo)
    {
        $this->userTokenRepo = $userTokenRepo;
    }

    public function createToken($userID, $token)
    {
        $this->userTokenRepo->model->create([
            'token' => $token,
            'user_id' => $userID
        ]);

        return true;
    }

    public function destroyUserToken($userID)
    {
        $this->userTokenRepo->model->where('user_id', $userID)->delete();
        return true;
    }
}