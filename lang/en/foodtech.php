<?php

/*
|--------------------------------------------------------------------------
| Foodtech storefront components (English)
|--------------------------------------------------------------------------
|
| Keys must match lang/id/foodtech.php exactly.
| The catering copy below is sample text; adjust it to the services actually offered.
|
*/

return [

    'nav' => [
        'my_orders' => 'My Orders',
        'admin'     => 'Admin Panel',
        'materials' => 'Materials',
    ],

    // Product card corner badges. Keys must match Product::BADGES.
    'badges' => [
        'promo'       => 'Promo',
        'discount'    => 'Discount',
        'best_seller' => 'Best seller',
        'new'         => 'New',
    ],

    'card' => [
        'detail' => 'View Details',
    ],

    'detail' => [
        'back'          => 'All products',
        'login_to_order' => 'Log in to order',
        'register'      => 'Create an account',
        'guest_note'    => 'The cart and checkout are available to customers who are logged in.',
        'order_wa'      => 'Ask or order on WhatsApp',
        'wa_message'    => 'Hello Lumeria.co, I am interested in :product.',
        'paylater'      => 'Simulate Paylater for this product',
        'related'       => 'More from Lumeria.co',
    ],

    'catering' => [
        'label'     => 'Lumeria Catering',
        'title'     => 'Melty treats for every occasion',
        'text'      => 'Order milk balls and melty desserts in bulk for gatherings, meetings, birthdays, or campus events. Tell us the quantity and the date and we will help work out the order.',
        'cta_order' => 'Order Catering',
        'cta_more'  => 'Learn More',
        'wa_message' => 'Hello Lumeria.co, I would like to ask about a catering order for an event.',
    ],

    'carousel' => [
        'title'  => 'Explore the Menu',
        'sub'    => 'Swipe to see every melty treat from Lumeria.co.',
        'all'    => 'See all products',
        'region' => 'Lumeria.co product list',
        'prev'   => 'Scroll left',
        'next'   => 'Scroll right',
        'dot'    => 'Go to page :n',
    ],

    'footer' => [
        'explore'    => 'Explore',
        'account'    => 'Account',
        'social'     => 'Social links',
        'whatsapp'   => 'WhatsApp',
        'chat_wa'    => 'Chat on WhatsApp',
        'instagram'  => 'Instagram',
        'wa_message' => 'Hello Lumeria.co, I would like to ask something.',
        'customer_service' => 'Customer Service',
        'how_to_order'     => 'How to Order',
        'track_order'      => 'Track Order',
        'contact_us'       => 'Contact Us',
        'payment'          => 'Payment',
        'pay_cash'         => 'Cash',
        'pay_paylater'     => 'Paylater',
        'pay_note'         => 'Installments of :min to :max months',
        'follow'           => 'Follow Us',
    ],

    'guest_order' => [
        'title' => 'Log in to place an order',
        'text'  => 'Log in or register to add products to your cart and check out. You can still try the Paylater simulator on this page without an account.',
    ],

    'history' => [
        'title'    => 'My Orders',
        'subtitle' => 'Your order history and status.',
        'count'    => ':count order|:count orders',
        'empty'    => 'No orders yet.',
        'order'    => 'Order',
        'date'     => 'Date',
        'items'    => 'Items',
        'payment'  => 'Payment',
        'total'    => 'Total',
        'status'   => 'Status',
        'view'     => 'View',
    ],

    'admin' => [
        'title'        => 'Admin Panel',
        'subtitle'     => 'Store overview, latest orders, and admin-only materials.',
        'stat_products'  => 'Active products',
        'stat_orders'    => 'Total orders',
        'stat_pending'   => 'Pending orders',
        'stat_customers' => 'Customers',
        'latest_orders'  => 'Latest orders',
        'no_orders'      => 'No orders yet.',
        'products'       => 'Product list',
        'category'       => 'Category',
        'price'          => 'Price',
        'badge'          => 'Badge',
        'active'         => 'Active',
        'yes'            => 'Yes',
        'no'             => 'No',
        'customer'       => 'Customer',
        'materials'      => 'Admin-only materials',
        'materials_text' => 'This section is not shown to guests or customers.',
    ],

];
