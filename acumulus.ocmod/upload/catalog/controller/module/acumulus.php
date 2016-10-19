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
     *   checkout/order/addOrder or checkout/order/addOrderHistory.
     * @param array $args
     *   Array with numeric indices containing the arguments as passed to the
     *   model method.
     *   When route = checkout/order/addOrder it contains: order (but without
     *   order_id as that will be created and assigned by the method).
     *   When route = checkout/order/addOrderHistory it contains: order_id,
     *   order_status_id, comment, notify, override.
     * @param mixed $output
     *   If passed by event checkout/order/addOrder it contains the order_id of
     *   the just created order. It is null for checkout/order/addOrderHistory.
     */
    public function eventOrderUpdate($route, $args, $output)
    {
        $order_id = $route === 'checkout/order/addOrderHistory' ? $args[0] : $output;
        $this->ocHelper->eventOrderUpdate((int) $order_id);
    }
}
