<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateCard extends Model
{
    protected $table = 'template_cards';

    protected $fillable = [
        'id',
        'name',
        'card_path',
        'type'
    ];
}
