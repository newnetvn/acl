<div class="row">
    <div class="col-12 col-md-6">
        @input(['name' => 'name', 'label' => __('acl::role.name')])
        @input(['name' => 'display_name', 'label' => __('acl::role.display_name')])
        @textarea(['name' => 'description', 'label' => __('acl::role.description')])
    </div>
    <div class="col-12 col-md-6">
        @if(is_admin())
            @checkbox(['name' => 'is_admin', 'label' => __('acl::role.is_admin')])
        @endif

        <div class="form-group">
            <label for="permissions" class="font-weight-600">{{ __('acl::role.permissions') }}</label>
            <newnet-tree name="permissions"
                         :data='@json(Permission::allTreeWithoutKey())'
                         :value='@json(json_decode(object_get($item, 'permissions')))'
            ></newnet-tree>
            @error('permissions')
                <span class="invalid-feedback text-left">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
