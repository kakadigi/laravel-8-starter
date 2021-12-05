<?php

namespace Modules\Membership\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Modules\Membership\Http\Requests\StoreUser;
use Modules\Membership\Http\Requests\UpdateMember;
use Modules\Membership\Http\Requests\LoginUser;
use Modules\Membership\Http\Requests\VerifyUser;
use Modules\Membership\Http\Requests\ResetRequest;
use Modules\Membership\Events\MemberRegistered;
use Modules\Membership\Events\ResetPassword;
use Modules\Membership\Member;
use Modules\Membership\MemberIdentity;
use Modules\Membership\Traits\ProfileTrait;

class MembershipApiController extends Controller
{
    use ProfileTrait;
    public function index(Request $request)
    {
        return response()->success($request->user()->load([
            'membership.identities'
        ]));
    }
    public function login(LoginUser $request, User $user)
    {
        $user = $user->where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $tokenName = config('app.name');
                $token = $user->createToken($tokenName)->accessToken;
                return response()->success([
                    'token' => $token,
                    'user' => $user->load('membership.identities')
                ], 200);
            } else {
                $user = null;
            }
        }
        return response()->fail($user, 401);
    }

    public function store(StoreUser $request, User $user)
    {
        DB::beginTransaction();
        try {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = bcrypt($request->password);
            $user = $user->create($data);
            $member = $this->splitFullName($data['name'] ?? '');
            $user->membership()->create($member->merge([
                'uuid' => Str::uuid()
            ])->toArray());
            event(new MemberRegistered($user->membership));
            $tokenName = config('app.name');

            DB::commit();
            return response()->success([
                'token' => $user->createToken($tokenName)->accessToken,
                'user' => $user->load('membership.identities')
            ], 201);
        } catch (\Throwable $e) {
            throw $e;
            DB::rollback();
            return response()->fail([
                'body' => 'Oops! register new member failed'
            ], 500);
        }
    }

    public function update(UpdateMember $request)
    {
        $member = $request->user()->membership ?? NULL;
        if ($member) {
            if ($request->has('identity')) {
                $identities = $member->identities ?? [];
                $items = collect($request->identity)->map(function ($value, $key) use ($identities) {
                    $typeDocument = $value['type'] ?? null;
                    if ($typeDocument) {
                        return [
                            'type' => $typeDocument,
                            'number' => $value['number'] ?? null,
                            'document' => is_file($value['document']) ? $value['document']->store('identities') : $identities[$key]->document ?? ''
                        ];
                    }
                })->toArray();
                $items = array_filter($items);
                if ($items) {
                    if ($member->identities()->count() > 0) {
                        $member->identities()->delete();
                    }
                    $member->identities()->createMany($items);
                }
            }

            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $data['image'] = $request->image->store('profiles', 'public');
            }

            if ($request->has('password')) {
                $user = $request->user();
                $user->password = bcrypt($request->password);
                $user->save();
            }

            if ($data) {
                $member->update($data);
            }

            return response()->success($request->user()->load('membership.identities'), 200);
        } else {
            return response()->fail($member, 401);
        }
    }

    public function verify(VerifyUser $request, Member $member)
    {
        if (!$member->verified) {
            $member->verified = !$member->verified;
            if ($member->save()) {
                $user = $member->user ?? NULL;
                if ($user) {
                    $user->markEmailAsVerified();
                }
            }
            $frontendUrl = config('membership.frontend_url') . '?verified=1';
            return redirect()->away($frontendUrl);
        } else {
            $frontendUrl = config('membership.frontend_url') . '?verified=0';
            return redirect()->away($frontendUrl);
        }
    }

    public function reset(ResetRequest $request, User $user)
    {
        $user = $user->where('email', $request->email)->first();
        if ($user) {
            event(new ResetPassword($user));
        }
        return $user ? response()->success($user) : response()->fail($user);
    }

    public function resendVerify(ResetRequest $request, User $user)
    {
        $user = $user->where('email', $request->email)->first();
        if ($user) {
            $member = $user->membership ?? NULL;
            if ($member) {
                event(new MemberRegistered($member));
            }
            return $member ? response()->success($member) : response()->fail($member);
        } else {
            return response()->fail($user, 401);
        }
    }

    public function previewIdentity(Request $request, MemberIdentity $identity)
    {
        if ($request->user()->id !== $identity->member->user->id) {
            return response()->fail('unauthorized', 403);
        }

        if (Storage::disk('local')->has($identity->document)) {
            $filePath = Storage::disk('local')->getAdapter()->getPathPrefix();
            $filePath .= $identity->document;
            return response()->file($filePath);
        }
    }

    public function show(Member $member)
    {
        return response()->success($member);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });
        return response()->success([
            'message' => 'Successfully logged out'
        ]);
    }
}
