<?php

namespace Inspector\CodeIgniter\NeuronAI;

use Inspector\Inspector;

class AgentMonitoring extends \Inspector\NeuronAI\AgentMonitoring
{
    public function __construct()
    {
        helper('inspector');
        parent::__construct(inspector());
    }
}
