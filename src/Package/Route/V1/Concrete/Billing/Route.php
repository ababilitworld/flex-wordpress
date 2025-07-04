<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Concrete\Billing;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V1\Base\Route as BaseRoute
};

class Route extends BaseRoute
{
    public function init(): void
    {
        $this->set_slug('billing');
        $this->set_route_slug('billing');
        $this->set_template_type('content');
        $this->set_template_part($this->get_billing_content());
        
        // Add query vars for invoice ID if needed
        $this->add_query_var('invoice_id');
        
        // Flush rewrite rules on first activation
        $this->enable_rewrite_flush();

        $this->init_service();
        $this->init_hook();
    }

    public function init_service(): void
    {
        //
    }

    public function init_hook(): void
    {
        //
    }

    protected function get_billing_content(): string
    {
        $invoice_id = get_query_var('invoice_id', 0);
        $invoice_data = $this->get_invoice_data($invoice_id);
        
        ob_start();
        ?>
        <div class="billing-container">
            <div class="invoice-header">
                <h1>Invoice #<?= esc_html($invoice_data['id']) ?></h1>
                <p>Date: <?= esc_html($invoice_data['date']) ?></p>
            </div>
            
            <div class="invoice-details">
                <h2>Billing Information</h2>
                <p>Name: <?= esc_html($invoice_data['customer_name']) ?></p>
                <p>Email: <?= esc_html($invoice_data['customer_email']) ?></p>
            </div>
            
            <table class="invoice-items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoice_data['items'] as $item): ?>
                    <tr>
                        <td><?= esc_html($item['name']) ?></td>
                        <td><?= esc_html($item['quantity']) ?></td>
                        <td><?= esc_html($item['price']) ?></td>
                        <td><?= esc_html($item['total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Subtotal</td>
                        <td><?= esc_html($invoice_data['subtotal']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">Tax</td>
                        <td><?= esc_html($invoice_data['tax']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">Total</td>
                        <td><?= esc_html($invoice_data['total']) ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="invoice-actions">
                <button class="print-invoice">Print Invoice</button>
                <button class="download-pdf">Download PDF</button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function get_invoice_data(int $invoice_id): array
    {
        // In a real implementation, this would query your database
        return [
            'id' => $invoice_id ?: '0001',
            'date' => date('F j, Y'),
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'items' => [
                [
                    'name' => 'Website Design',
                    'quantity' => 1,
                    'price' => '$500.00',
                    'total' => '$500.00'
                ],
                [
                    'name' => 'Hosting (1 year)',
                    'quantity' => 1,
                    'price' => '$120.00',
                    'total' => '$120.00'
                ]
            ],
            'subtotal' => '$620.00',
            'tax' => '$49.60',
            'total' => '$669.60'
        ];
    }

    public function register(): void
    {
        parent::register();
        
        add_action('wp_enqueue_scripts', [$this, 'enqueue_billing_assets']);
        add_action('wp_footer', [$this, 'add_billing_scripts']);
    }

    public function enqueue_billing_assets(): void
    {
        if (get_query_var('billing')) {
            wp_enqueue_style(
                'billing-styles',
                get_template_directory_uri() . '/Asset/Appearence/Template/Invoice/css/invoice.css'
            );
        }
    }

    public function add_billing_scripts(): void
    {
        if (get_query_var('billing')) {
            ?>
            <script>
            jQuery(document).ready(function($) {
                $('.print-invoice').on('click', function() {
                    window.print();
                });
                
                $('.download-pdf').on('click', function() {
                    // PDF generation logic
                    alert('PDF generation would happen here');
                });
            });
            </script>
            <?php
        }
    }
}