<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Requests\Device\UpdateDeviceRequest;
use App\Models\Device;

class DeviceController
{
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());

        return response()->json([
            'name' => $device->name,
        ]);
    }

}
