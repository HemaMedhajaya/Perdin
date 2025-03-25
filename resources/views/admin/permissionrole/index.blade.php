@extends('layouts.admin')
@section('title', 'Permission')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-5"><b>Permission</b></h5>
                    <div class="mb-2">
                        <input type="hidden" name="role_id" id="role_id" value="{{ $id }}">
                        @foreach ($data as $d)
                            <div class="row">
                                <div class="col-md-3">
                                    {{ $d['name'] }}
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        @foreach ($d['group'] as $g)
                                            <div class="col-md-3">
                                                <input class="form-check-input permission-checkbox"
                                                    type="checkbox"
                                                    value="{{ $g['id'] }}"
                                                    data-role-id="{{ $id }}"
                                                    data-permission-id="{{ $g['id'] }}"
                                                    id="permission_{{ $g['id'] }}"
                                                    {{ in_array($g['id'], $assignedPermissions) ? 'checked' : '' }}> 
                                                <label class="form-check-label" for="permission_{{ $g['id'] }}">
                                                    {{ $g['name'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
<script>
    var routes = {
        permissionroleData: "{{ route('permission-role.toggle') }}"
    }
    var csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/permissionrole.js') }}"></script>
@endsection
