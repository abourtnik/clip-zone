<div class="row align-items-start my-3">
    <div class="col-12 col-xl-4">
        <h2>Security</h2>
        <p class="text-muted">Manage your account security</p>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card shadow-soft my-3">
            <form action="{{ route('user.update.phone') }}" method="POST">
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3" x-data="{selected: {flag : '🇫🇷', code : '+33'}}">
                            <label for="phone" class="form-label">Phone Number</label>
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span x-text="selected.flag"></span>
                                    <span x-text="selected.code"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach(['🇫🇷' => '+33', '🇺🇸' => '+1'] as $flag => $code)
                                        <li x-show="selected.flag !== '{{$flag}}'">
                                            <button class="dropdown-item d-flex align-items-center gap-2" @click="selected = {flag : '{{$flag}}', code : '{{$code}}' }">
                                                <span>{{$flag}}</span>
                                                <span>{{$code}}</span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="code" :value="selected.code" required>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <button class="btn btn-primary" type="submit">Send Code</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-user-lock"></i>
                        Update Security Information
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
