<div class="row">
    <div class="col-12 col-md-6">
        @input(['name' => 'name', 'label' => __('acl::user.name')])

        <input type="email" name="email" autocomplete="email" disabled style="height: 0px; border: 0; opacity: 0; position: absolute;">
        @input(['name' => 'email', 'label' => __('acl::user.email'), 'type' => 'email'])

        <input type="password" autocomplete="password" disabled style="height: 0px; border: 0; opacity: 0; position: absolute;">
        @input(['name' => 'password', 'label' => __('acl::user.password'), 'type' => 'password'])

        @if(is_admin())
            @checkbox(['name' => 'is_admin', 'label' => '', 'placeholder' => __('acl::user.is_admin')])
        @endif

        @sumoselect(['name' => 'roles', 'label' => __('acl::user.roles'), 'options' => get_acl_role_options(), 'multiple' => true])
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="permissions" class="font-weight-600">{{ __('acl::user.permissions') }}</label>
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
