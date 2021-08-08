#### Traits
    - HasDevices
    - HasHashedPassword
    - HasPasswordHistory
    
<br><hr><br>


#### Devices (Agents)
    - $user->detectDevice(),
    - $user->devices,
    - $user->removeDevice(19),
    - $user->removeAllDevices(),
    
    - dd(agent());    Helper
    - AgentDetector::class  Middleware


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
        
