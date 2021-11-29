<?php

namespace Modules\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Membership\MemberIdentity;
use App\Models\User;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'phone',
        'image',
        'address',
        'state',
        'province',
        'city',
        'subdistrict',
        'gender'
    ];
    protected $hidden = ['created_at', 'updated_at', 'user_id'];
    protected $appends = ['image_url'];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function identities()
    {
        return $this->hasMany(MemberIdentity::class, 'member_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
