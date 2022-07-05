<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Traits\Api\RequestValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    use RequestValidator;

    public function store(Request $request) {
        try{
            $this->validate($request, [
                'name'      => 'required|min:3|max:100',
                'email'     => 'required|email|unique:members',
                'phone'     => 'required|numeric|digits_between:6,20',
                'hobbies'   => 'required|array',
                'hobbies.*' => 'string|max:100',
            ]);
            
            $member = Member::create([
                'user_id'   => auth()->id(),
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
            ]);

            $hobbies = collect($request->hobbies)->map(fn($item) => [
                'name'          => $item,
                'member_id'     => $member->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $member->hobbies()->insert($hobbies->toArray());
            $member->load('hobbies');

            return ResponseHelper::make(
                MemberResource::make($member)
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
