<?php

use Pharaonic\Laravel\Users\Classes\AgentDetector;

/**
 * Getting Agent Object
 *
 * @return Agent
 */
function agent()
{
    return app()->has('Agent') ? app('Agent') : app()->instance('Agent', new AgentDetector);
}
