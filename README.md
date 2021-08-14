#### Traits
    - HasDevices

    - HasHashedPassword
    - HasPasswordHistory

    - HasRoles
    - HasPermissions
    
<br><hr><br>


#### Devices (Agents)
    - $user->hasDetectedDevice()
    - $user->detectDevice()
    - $user->devices
    - $user->fcmList
    - $user->removeDevice(3)   device-id
    - $user->removeAllDevices()
    
    - agent()   Helper
    - agent     Middleware


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
        
#### Roles
    -