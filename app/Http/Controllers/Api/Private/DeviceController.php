<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Requests\Device\UpdateDeviceRequest;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;

class DeviceController
{
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());

        return response()->json([
            'name' => $device->name,
        ]);
    }

    public function delete(Device $device): RedirectResponse
    {
        $device->delete();

        return back();
    }

}
