<?php

use Siel\Acumulus\Api;
use Siel\Acumulus\Invoice\Result;
use Siel\Acumulus\Invoice\Source;

/**
 * This extension contains example code to:
 * - Customise the invoice before it is sent to Acumulus.
 * - Process the results of sending the invoice to Acumulus.
 *
 * @property \Loader $load
 * @property \ModelExtensionEvent $model_extension_event
 */
class ControllerExtensionModuleCustomiseAcumulusInvoice extends Controller
{
    /** @var \Siel\Acumulus\Helpers\ContainerInterface */
    protected static $container = null;

    /**
     * Constructor.
     *
     * @param \Registry $registry
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        if (static::$container === NULL) {
            static::$container = new \Siel\Acumulus\Helpers\Container($this->getShopNamespace(), 'nl');
        }
    }

    /**
     * Returns the Shop namespace to use for this OC version.
     *
     * @return string
     *   The Shop namespace to use for this OC version.
     */
    protected function getShopNamespace()
    {
        $result = sprintf('OpenCart\OpenCart%1$u\OpenCart%1$u%2$u', substr(VERSION, 0, 1), substr(VERSION, 2, 1));
        return $result;
    }

    /**
     * Install controller action, called when the module is installed.
     */
    public function install()
    {
        $this->installEvents();
    }

    /**
     * Uninstall function, called when the module is uninstalled by an admin.
     */
    public function uninstall()
    {
        $this->uninstallEvents();
    }

    /**
     * Installs our events.
     *
     * This will add them to the table 'event' from where they are registered on
     * the start of each request. The controller actions can be found below.
     */
    protected function installEvents()
    {
        $this->uninstallEvents();
        $this->model_extension_event->addEvent('customise_acumulus_invoice', 'admin/model/extension/module/acumulus/invoiceSend/before', 'extension/module/customise_acumulus_invoice/invoiceSendBefore');
        $this->model_extension_event->addEvent('customise_acumulus_invoice', 'admin/model/extension/module/acumulus/invoiceSend/after', 'extension/module/customise_acumulus_invoice/invoiceSendAfter');
    }

    /**
     * Removes the Acumulus event handlers from the event table.
     */
    protected function uninstallEvents()
    {
        $this->load->model('extension/event');
        $this->model_extension_event->deleteEvent('customise_acumulus_invoice');
    }

    /**
     * Processes the event triggered before an invoice will be sent to Acumulus.
     *
     * @param array $invoice
     *   The invoice in Acumulus format as will be sent to Acumulus, see
     *   https://apidoc.sielsystems.nl/content/invoice-add.
     * @param \Siel\Acumulus\Invoice\Result $localResult
     *   Any local error or warning messages that were created locally.
     * @param \Siel\Acumulus\Invoice\Source $invoiceSource
     *   The original OC order for which te invoice has been created.
     */
    public function invoiceSendBefore(array &$invoice, Result $localResult, Source $invoiceSource)
    {
        // Here you can make changes to the invoice based on your specific
        // situation, e.g. setting the payment status to its correct value:
        $invoice['customer']['invoice']['paymentstatus'] = $this->isOrderPaid($invoiceSource) ? Api::PaymentStatus_Paid : Api::PaymentStatus_Due;
    }

    /**
     * Processes the event triggered after an invoice has been sent to Acumulus.
     *
     * You can add warnings and errors to the result and they will be mailed,
     * but you can no longer influence (not) saving the entryid and token.
     *
     * @param array $invoice
     *   The invoice in Acumulus format as will be sent to Acumulus, see
     *   https://apidoc.sielsystems.nl/content/invoice-add.
     * @param \Siel\Acumulus\Invoice\Source $invoiceSource
     *   The original OC order for which te invoice has been created.
     * @param \Siel\Acumulus\Invoice\Result $result
     *   The result as sent back by Acumulus.
     */
    public function invoiceSendAfter(array $invoice, Source $invoiceSource, Result $result)
    {
        if ($result->getException()) {
            // Serious error:
            if ($result->isSent()) {
                // During sending.
            }
            else {
                // Before sending.
            }
        }
        elseif ($result->hasError()) {
            // Invoice was sent to Acumulus but not created due to errors in invoice.
        }
        else {
            // Sent successfully, invoice has been created in Acumulus:
            if ($result->getWarnings()) {
                // With warnings.
            }
            else {
                // Without warnings.
            }
        }
    }

    /**
     * Returns if the order has been paid or not.
     *
     * OpenCart does not store any paynent data, so determining the payment
     * status is not really possible for the Acumulus extension. Therefore this
     * is a very valid example change you may want to make to the invoice
     * before it is being sent.
     *
     * Please fill in your own logic here in this method.
     *
     * @param \Siel\Acumulus\Invoice\Source $invoiceSource
     *   The original order for which the invoice is being made.
     *
     * @return bool
     *   True if the order has been paid, false otherwise.
     *
     */
    protected function isOrderPaid(Source $invoiceSource)
    {
        static::$container->getLog()->info('ControllerExtensionModuleCustomiseAcumulusInvoice::isOrderPaid(): invoiceSource = ' . var_export($invoiceSource->getSource(), true));
        return true;
    }
}
