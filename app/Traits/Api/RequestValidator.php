<?php

namespace App\Traits\Api;

use App\Exceptions\ErrorException as Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait RequestValidator{
    public function validate(
        Request $request,
        array $rules,
        array $message=[],
        array $customAttribute=[]
    ) {
        $validator = Validator::make(
            $request->all(),
            $rules,
            $message,
            $customAttribute,
        );

        if($validator->fails()) {
            $errors = $validator->errors()->all();
            return throw new Error('Unprocessable', 422, $errors);
        }

        return $validator;
    }
}