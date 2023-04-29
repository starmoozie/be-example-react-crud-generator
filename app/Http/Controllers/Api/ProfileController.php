<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Profile\UpdateRequest;
use App\Http\Requests\Api\Profile\UploadRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\Resource;
use App\Constants\HttpCode;

class ProfileController extends Controller
{
    use \App\Traits\ResponseMessageTrait;
    use Resources\UploadTrait;

    protected $with = ['photos'];

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = \Auth()->user();
        $user->load(['photos']);

        return $this->success(new Resource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        $user = \Auth::user();
        $user->update($request->validated());

        foreach ($request->photos as $key => $photo) {
            $photo['active'] = !$key ? true : false;
            $user->photos()->updateOrCreate(['location' => $photo['location']], $photo);
        }

        return $this->success([], null, HttpCode::CREATED);
    }

    /**
     * Upload photos resource in storage.
     */
    public function upload(UploadRequest $request)
    {
        foreach ($request->photos as $value) {
            $request->merge(['new_photo' => $value]);

            $upload[] = $this->uploadFile($request, 'new_photo');
        }

        return $this->success($upload, null, HttpCode::CREATED);
    }
}
