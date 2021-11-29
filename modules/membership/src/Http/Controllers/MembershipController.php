<?php

namespace Modules\Membership\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Membership\MemberIdentity;

class MembershipController extends Controller
{

    public function previewIdentity(Request $request, MemberIdentity $identity)
    {
        if (Storage::disk('local')->has($identity->document)) {
            $filePath = Storage::disk('local')->getAdapter()->getPathPrefix();
            $filePath .= $identity->document;
            return response()->file($filePath);
        }
    }
}
