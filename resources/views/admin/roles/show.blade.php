@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
    <x-breadcrumb :items="$breadcrumbItems" />
    <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="col-12">
                    <div class="form-floating form-floating-outline">
                        <input type="text" id="dataName" name="name" class="form-control" placeholder="Enter your name"
                            value="{{ $role->name }}" readonly />
                        <label for="name">Name</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            @foreach ($modulePermissions as $moduleName => $permissions)
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="m-0 me-2">{{ $moduleName }}</h5>
                            </div>
                        </div>
                        <div class="card-body" style="height: 200px; overflow-y: auto;">

                            @foreach ($permissions as $permission)
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="permission[]"
                                        value="{{ $permission['name'] }}" id="permission{{ $permission['id'] }}"
                                        @if (in_array($permission['id'], $rolePermissions ?? [])) checked @endif />
                                    <label class="form-check-label" for="permission{{ $permission['id'] }}">
                                        {{ $permission['name'] }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </div>
    </form>

@endsection
