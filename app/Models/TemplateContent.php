<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateContent extends Model
{
    protected $table = 'template_contents';

    protected $fillable = [
        'id',
        'name',
        'preview_image',
        'font_name',
        'content',
        'status'
    ];
}
