<?php

use Siel\Acumulus\OpenCart\OpenCart2\Helpers\OcHelper;

/** @noinspection PhpUndefinedClassInspection */
/**
 * Class ControllerModuleAcumulus is the Acumulus admin site controller.
 */
class ControllerModuleAcumulus extends Controller
{
    /** @var \Siel\Acumulus\OpenCart\OpenCart2\Helpers\OcHelper */
    static private $staticOcHelper = null;

    /** @var \Siel\Acumulus\OpenCart\OpenCart2\Helpers\OcHelper */
    private $ocHelper = null;

    /**
     * Constructor.
     *
     * @param \Registry $registry
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        if ($this->ocHelper === NULL) {
            if (static::$staticOcHelper === NULL) {
                // Load autoloader and then our helper that contains OC1 and OC2
                // shared code.
                require_once(DIR_SYSTEM . 'library/Siel/psr4.php');
                static::$staticOcHelper = new OcHelper($this->registry, 'OpenCart\OpenCart2\OpenCart23');
            }
            $this->ocHelper = static::$staticOcHelper;
        }
    }

    /**
     * Install controller action, called when the module is installed.
     */
    public function install()
    {
        $this->ocHelper->install();
    }

    /**
     * Uninstall function, called when the module is uninstalled by an admin.
     */
    public function uninstall()
    {
        $this->ocHelper->uninstall();
    }

    /**
     * Main controller action: show/process the basic settings form for this
     * module.
     */
    public function index()
    {
        $this->ocHelper->config();
    }

    /**
     * Controller action: show/process the advanced settings form for this
     * module.
     */
    public function advanced()
    {
        $this->ocHelper->advancedConfig();
    }

    /**
     * Controller action: show/process the batch form for this module.
     */
    public function batch()
    {
        $this->ocHelper->batch();
    }

    /**
     * Explicit confirmation step to allow to retain the settings.
     *
     * The normal uninstall action will unconditionally delete all settings.
     */
    public function confirmUninstall()
    {
        $this->ocHelper->confirmUninstall();
    }

    /**
     * Adds our menu-items to the admin menu.
     *
     * @param string $route
     *   The current route (common/column_left).
     * @param array $data
     *   The data as will be passed to the view.
     */
    public function eventViewColumnLeft($route, &$data)
    {
        if ($this->user->hasPermission('access', 'module/acumulus')) {
            $this->ocHelper->eventViewColumnLeft($data['menus']);
        }
    }
}
