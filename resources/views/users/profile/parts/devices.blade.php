<div x-data="{device : {}}">
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Connected Devices</h2>
            <p class="text-muted">See devices using your account</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <div class="card-body">
                    @if($user->devices->isEmpty())
                        <div class="alert alert-primary mb-0">
                            You haven't connected any device yet
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col" style="min-width: 200px">Device</th>
                                    <th scope="col" style="min-width: 120px">Last activity</th>
                                    <th scope="col" style="min-width: 120px">Created at</th>
                                    <th scope="col" style="min-width: 200px">Expires at</th>
                                    <th scope="col" style="min-width: 6px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->devices as $device)
                                    <tr>
                                        <td>
                                            <dynamic-input value="{{$device->name}}" name="name" url="{{route('devices.update', $device)}}" maxlength="50" />
                                        </td>
                                        <td>{{$device->last_used_at?->diffForHumans()}}</td>
                                        <td>{{$device->created_at->diffForHumans()}}</td>
                                        <td>{{$device->expires_at?->format('d F Y H:i') ?? 'Never'}}</td>
                                        <td>
                                            <button
                                                class="btn btn-danger btn-sm"
                                                title="Delete device"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete_device"
                                                data-name="{{$device->name}}"
                                                data-route="{{route('devices.delete', $device)}}"
                                                @click="device = $el.dataset;"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('users.profile.modals.delete_device')
</div>
