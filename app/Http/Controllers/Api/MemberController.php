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


    public function index() {
        $members = Member::get();

        return ResponseHelper::make(
            MemberResource::collection($members)
        );
    }


    public function show(Member $member) {
        try{
            return ResponseHelper::make(
                MemberResource::make($member->load('hobbies'))
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


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
    
    
    public function update(Request $request, Member $member) {
        try{
            $this->validate($request, [
                'name'      => 'nullable|min:3|max:100',
                'email'     => 'nullable|email|unique:members,email,' . $member->id,
                'phone'     => 'nullable|numeric|digits_between:6,20',
                'hobbies'   => 'nullable|array',
                'hobbies.*' => 'string|max:100',
            ]);
            
            $member->update([
                'name'      => $request->name ?? $member->name,
                'email'     => $request->email ?? $member->email,
                'phone'     => $request->phone ?? $member->phone,
            ]);

            if(!empty($request->hobbies)) {
                $hobbies = collect($request->hobbies)->map(fn($item) => [
                    'name'          => $item,
                    'member_id'     => $member->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $member->hobbies()->delete();
                $member->hobbies()->insert($hobbies->toArray());
            }

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
