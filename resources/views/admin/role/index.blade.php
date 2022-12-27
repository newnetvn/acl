@extends('core::admin.master')

@section('meta_title', __('acl::role.index.page_title'))

@section('page_title', __('acl::role.index.page_title'))

@section('page_subtitle', __('acl::role.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('acl::role.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('acl::role.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        <a href="{{ route('admin.role.create') }}" class="action-item"><i class="fa fa-plus"></i> {{ __('core::button.add') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('acl::role.name') }}</th>
                    <th>{{ __('acl::role.display_name') }}</th>
                    <th>{{ __('acl::role.description') }}</th>
                    <th>{{ __('acl::role.is_admin') }}</th>
                    <th>{{ __('acl::role.created_at') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                    	<td>{{ $role->id }}</td>
                        <td>
                            <a href="{{ route('admin.role.edit', $role->id) }}">
                                {{ $role->name }}
                            </a>
                        </td>
                        <td>{{ $role->display_name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>
                            @if($role->is_admin)
                                <i class="fa fa-check text-success"></i>
                            @endif
                        </td>
                        <td>{{ $role->created_at }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <table-button-delete url-delete="{{ route('admin.role.destroy', $role->id) }}"></table-button-delete>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
