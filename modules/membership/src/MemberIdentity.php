<?php

namespace Modules\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MemberIdentity extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['image_url'];

    public function member()
    {
        return $this->belongsTo('\Modules\Membership\Member', 'member_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        return ($this->document) ? "/api/membership/preview-identity/{$this->id}" : '#';
    }
}
