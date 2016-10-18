<?php

use Siel\Acumulus\OpenCart\Helpers\OcHelper;

/** @noinspection PhpUndefinedClassInspection */

/**
 * Class ControllerModuleAcumulus is the Acumulus catalog site controller.
 */
class ControllerModuleAcumulus extends Controller
{
    /** @var \Siel\Acumulus\OpenCart\Helpers\OcHelper */
    private $ocHelper = null;

    /**
     * Constructor.
     *
     * @param \Registry $registry
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        if ($this->ocHelper === null) {
            // Load autoloader and then our helper that contains OC1 and OC2
            // and admin and catalog shared code.
            require_once(DIR_SYSTEM . 'library/Siel/psr4.php');
            $this->ocHelper = new OcHelper($this->registry, 'OpenCart\OpenCart2\OpenCart23');
        }
    }

    /**
     * Event handler that executes on the creation or update of an order.
     *
     * @param string $route
     * @param mixed $output
     * @param int $order_id
     * param int $order_status_id
     */
    public function eventOrderUpdate($route, $output, $order_id/*, $order_status_id*/)
    {
        $this->ocHelper->eventOrderUpdate((int) $order_id);
    }
}
