<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 
        'phone', 
        'address_1',
        'address_2', 
        'logo_url', 
        'greeting_msg',
        'post_code',
        'contact_name',
        'contact_email',
        'company_id',
        'company_name'
    ];
    protected $table = 'restaurants';

    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'restaurant_id', 'id');
    }
}
