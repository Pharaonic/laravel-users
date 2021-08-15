#### Traits

    - HasDevices

    - HasHashedPassword
    - HasPasswordHistory

    - HasRoles
    - HasPermissions

<br><hr><br>

#### Devices (Agents)

    - $user->hasDetectedDevice()
    - $user->detectDevice($fcm)
    - $user->detectDevice()
    - $user->devices
    - $user->fcmList
    - $user->removeDevice(3)   device-id
    - $user->removeAllDevices()

    - agent()   Helper
    - agent     Middleware

<br><hr><br>

#### Password

    - Hashed Password
    - Password History
        - $user->passwordHistory
            - created_at
            - pass_from
            - pass_to
            - ip
            - agent
                - device
                - operationSystem
                - browser

<br><hr><br>

#### Roles

    - Create
        Permission::create('test.something', 'Test Someth2ing')
        Permission::create('test.something', 'Test Something', 'en')
        Permission::create('test.something', [
            'ar'    => 'تجربة شئ',
            'en'    => 'Test Something'
        ])


        Role::create('admin', 'Administrator');
        Role::create('admin', 'Administrator', 'en');
        Permission::create('test.something', [
            'ar'    => 'مدير',
            'en'    => 'Administrator'
        ])
    

    - Actions
        $user->permissionsList,
        $user->permit('*', 'asd'),
        $user->permitted('test.something'),
        $user->permittedAny('test.something'),
        $user->forbid('*'),
        $user->forbad('test.something'),
        $user->forbadAny('test.something'),
        $user->syncPermissions('*', 'test.something'),


        $user->rolesList,
        $user->entrust('test.something', 'admin'),
        $user->entrusted('admin'),
        $user->entrustedAny('*', 'admin'),
        $user->distrust('admin'),
        $user->distrusted('admin'),
        $user->distrustedAny('*', 'admin'),
        $user->syncRoles('*', 'admin'),


    - Middlewares
        middleware('entrusted:admin')
        middleware('permitted:test.something')
