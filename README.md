#### Devices (Agents)
    - User::first()->detectDevice(),
    - User::first()->devices,
    - User::first()->removeDevice(19),
    - User::first()->removeAllDevices(),
    - dd(agent());    Helper
    - AgentDetector::class  Middleware