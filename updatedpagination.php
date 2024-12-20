<?php
/*
 * Plugin Name: Zappit AI - SEO 
 * Description: AI-powered SEO optimization and content generation.
 * Version: 0.1
 * Author: Zappit AI
 * Author URI: https://zappit.ai
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$zappit_auth_token = "eyJhbGciOiJSUzI1NiIsImtpZCI6IjNmZDA3MmRmYTM4MDU2NzlmMTZmZTQxNzM4YzJhM2FkM2Y5MGIyMTQiLCJ0eXAiOiJKV1QifQ.eyJuYW1lIjoiQiBUcmFjayIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NKUmJ2b05OWGd6dWVmSU81VE13V2ptUjUwa1V0SWFObWtzT1k3OGVlN3U4RHRRVDR3PXM5Ni1jIiwiaXNzIjoiaHR0cHM6Ly9zZWN1cmV0b2tlbi5nb29nbGUuY29tL29uZWxpbmstZGV2ZWxvcGVyIiwiYXVkIjoib25lbGluay1kZXZlbG9wZXIiLCJhdXRoX3RpbWUiOjE3MzI0MzI3NDAsInVzZXJfaWQiOiJqOVFtTFFPZVU1TjR4MDBDbExuNjBHYnRtVVkyIiwic3ViIjoiajlRbUxRT2VVNU40eDAwQ2xMbjYwR2J0bVVZMiIsImlhdCI6MTczMjk2OTI3OSwiZXhwIjoxNzMyOTcyODc5LCJlbWFpbCI6ImJ0cmFjay5tYWluQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJmaXJlYmFzZSI6eyJpZGVudGl0aWVzIjp7Imdvb2dsZS5jb20iOlsiMTA1MTk3MDgwMTIzMzQ1MjEwNjc1Il0sImVtYWlsIjpbImJ0cmFjay5tYWluQGdtYWlsLmNvbSJdfSwic2lnbl9pbl9wcm92aWRlciI6Imdvb2dsZS5jb20ifX0.Kd1pHv16y4e_DPLsuGOO6vrNjf88riFJt-rOaqOEJpf64wMH0QkzcFDrGfWeNor6yeIBUkj4KBHXEfm1s4NXCOR_Dk3A55a--KpImJjQEjcv3bso1UxTsM5mV_nQGO83UHkfA91nPK85vVgspvgX2Aoq2hcs-Yn5q5Ko7k88Np9xlVs1YCMms7ZURZLkBk-billrQ86iL_M7aHP5L1RQXC1hW8AEPrHKg-NKp8vnN_YB-qjMMBcFlR2qNdE1SOdaPiOWwtKUxkJSNcU0IZn6r2eo2oCIuYrqHaPdcYFXm0MBDyNbT7I7nwiT15RfDYl1tIBwObSYVoNjHvrqyq8gmA";

$plugin_assets_folder = plugin_dir_url(__FILE__) . 'assets';

// Add activation & deactivation hooks
register_activation_hook(__FILE__, 'zappit_ai_set_defaults');
register_activation_hook(__FILE__, 'create_zappit_ai_new_content_table');
register_deactivation_hook(__FILE__, 'reset_plugin_options_on_deactivation');
register_deactivation_hook(__FILE__, 'drop_zappit_table');

function zappit_ai_set_defaults()
{
    if (!get_option('zappit_ai_api_key')) {
        update_option('zappit_ai_api_key', '');
    }
    if (!get_option('zappit_ai_company_name')) {
        update_option('zappit_ai_company_name', 'Zappit AI');
    }
    if (!get_option('zappit_ai_company_tagline')) {
        update_option('zappit_ai_company_tagline', 'AI-powered SEO optimization and content generation.');
    }
    if (!get_option('zappit_ai_company_logo_url')) {
        update_option('zappit_ai_company_logo_url', 'https://zappit.ai/assets/images/logo4.jpg');
    }
    if (!get_option('zappit_ai_meta_title')) {
        update_option('zappit_ai_meta_title', get_option('blogname'));
    }
    if (!get_option('zappit_ai_meta_description')) {
        update_option('zappit_ai_meta_description', get_option('blogdescription'));
    }
    if (!get_option('zappit_ai_plugin_name')) {
        update_option('zappit_ai_plugin_name', 'Zappit AI - SEO');
    }
    if (!get_option('zappit_ai_plugin_description')) {
        update_option('zappit_ai_plugin_description', 'AI-powered SEO optimization and content generation.');
    }
    if (!get_option('zappit_ai_plugin_author')) {
        update_option('zappit_ai_plugin_author', 'Zappit AI');
    }
    if (!get_option('zappit_ai_plugin_author_uri')) {
        update_option('zappit_ai_plugin_author_uri', 'https://zappit.ai');
    }
}

function reset_plugin_options_on_deactivation()
{
    delete_option('zappit_ai_api_key');
    
    delete_option('zappit_ai_company_name');
    delete_option('zappit_ai_company_logo_url');

    delete_option('zappit_ai_plugin_name');
    delete_option('zappit_ai_plugin_description');
    delete_option('zappit_ai_plugin_author');
    delete_option('zappit_ai_plugin_author_uri');

    delete_option('zappit_ai_meta_title');
    delete_option('zappit_ai_meta_description');
    delete_option('zappit_ai_meta_keywords');
    delete_option('zappit_ai_meta_og_image');
}

// Hook for adding admin menus
add_action('admin_menu', 'zappit_ai_menu');

function create_slug($string)
{
    // Convert to lowercase
    $string = strtolower($string);

    // Replace non-alphanumeric characters (except for spaces) with a hyphen
    $string = preg_replace('/[^a-z0-9 -]/', '', $string);

    // Replace spaces and multiple hyphens with a single hyphen
    $string = preg_replace('/[ -]+/', '-', $string);

    // Trim any leading or trailing hyphens
    $string = trim($string, '-');

    return $string;
}

function create_slug_($string)
{
    // Convert to lowercase
    $string = strtolower($string);

    // Replace non-alphanumeric characters (except for spaces) with a hyphen
    $string = preg_replace('/[^a-z0-9 -]/', '', $string);

    // Replace spaces and multiple hyphens with a single hyphen
    $string = preg_replace('/[ -]+/', '_', $string);

    // Trim any leading or trailing hyphens
    $string = trim($string, '-');

    return $string;
}

function create_zappit_ai_new_content_table() {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . 'zappit_ai_new_content'; // Adds WordPress table prefix
    $charset_collate = $wpdb->get_charset_collate();

    // Define the SQL for the table
    $sql = "CREATE TABLE $table_name (
        post_id BIGINT(20) UNSIGNED NOT NULL,
        reco_id TEXT NOT NULL,
        title TEXT,
        description TEXT,
        keywords TEXT,
        og_image TEXT,
        status TEXT,
        PRIMARY KEY (post_id)
    ) $charset_collate;";

    // Include the WordPress upgrade script
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function drop_zappit_table() {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . 'zappit_ai_new_content';

    // SQL to drop the table
    $sql = "DROP TABLE IF EXISTS $table_name;";

    // Execute the SQL query
    $wpdb->query($sql);
}

// Function to add menu pages
function zappit_ai_menu()
{
    $companyName = get_option('zappit_ai_company_name');
    $companyTagline = get_option('zappit_ai_company_tagline');
    $menuPageTitle = ($companyTagline) ? $companyName . ' | ' . $companyTagline : $companyName;

    add_menu_page(
        $menuPageTitle, // Page title
        $companyName, // Menu title
        'manage_options', // Capability
        create_slug($companyName), // Menu slug
        'zappit_ai_page', // Function to display the page
        'dashicons-superhero',
        2
    );
}

// Function to truncate text
function zappit_ai_truncate_text($text, $length = 50)
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function get_current_page_link()
{
    $current_url = home_url(add_query_arg(null, null));
    return $current_url;
}

function zappit_wp_api_validate($value)
{
    $api_url = 'https://87b3-49-205-43-13.ngrok-free.app/wp/validate-access-key'; // Replace with your actual API endpoint

    // Set up the arguments for the POST request
    $args = [
        'body' => json_encode(['apiKey' => $value]), // Add more data if necessary
        'headers' => [
            'Referer' => home_url(),
            'Content-Type' => 'application/json', // Specify JSON format
        ],
        'method' => 'POST',
    ];

    // Send the POST request
    $response = wp_remote_post($api_url, $args);
   
    if (is_wp_error($response)) {
        // Handle the error appropriately (log it, display an error message, etc.)
        return [
            'valid' => false,
            'message' => 'WP Error: error encountered while handling Zappit API response'
        ];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if the API response indicates a valid input
    return $data;
}

function zappit_get_new_content_actions($value)
{
    global $zappit_auth_token;

    $api_url = 'https://onelink-developer.spotlightapis.com/getZappitActions'; // Replace with your actual API endpoint

    // Set up the arguments for the POST request
    $args = [
        'body' => json_encode(['domainId' => 'thetorchguys.com', 'recommendationType' => 'NEWCONTENT']), // Add more data if necessary
        'headers' => [
            'Referer' => home_url(),
            'Content-Type' => 'application/json', // Specify JSON format
            'Authorization' => 'Bearer ' . $zappit_auth_token,
        ],
        'method' => 'POST',
    ];

    // Send the POST request
    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        // Handle the error appropriately (log it, display an error message, etc.)
        return [
            'success' => false,
            'message' => 'WP Error: error encountered while handling Zappit API response'
        ];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if the API response indicates a valid input
    return $data;
}

function zappit_get_technical_actions($value)
{
    global $zappit_auth_token;

    $api_url = 'https://onelink-developer.spotlightapis.com/getZappitActions'; // Replace with your actual API endpoint

    // Set up the arguments for the POST request
    $args = [
        'body' => json_encode(['domainId' => 'thetorchguys.com', 'recommendationType' => 'TECHNICAL']), // Add more data if necessary
        'headers' => [
            'Referer' => home_url(),
            'Content-Type' => 'application/json', // Specify JSON format
            'Authorization' => 'Bearer ' . $zappit_auth_token,
        ],
        'method' => 'POST',
    ];

    // Send the POST request
    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        // Handle the error appropriately (log it, display an error message, etc.)
        return [
            'success' => false,
            'message' => 'WP Error: error encountered while handling Zappit API response'
        ];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if the API response indicates a valid input
    return $data;
}

function zappit_get_page_fixes_actions($value)
{
    global $zappit_auth_token;

    $api_url = 'https://onelink-developer.spotlightapis.com/getZappitActions'; // Replace with your actual API endpoint

    // Set up the arguments for the POST request
    $args = [
        'body' => json_encode(['domainId' => 'thetorchguys.com', 'recommendationType' => 'PAGEFIXES']), // Add more data if necessary
        'headers' => [
            'Referer' => home_url(),
            'Content-Type' => 'application/json', // Specify JSON format
            'Authorization' => 'Bearer ' . $zappit_auth_token,
        ],
        'method' => 'POST',
    ];

    // Send the POST request
    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        // Handle the error appropriately (log it, display an error message, etc.)
        return [
            'success' => false,
            'message' => 'WP Error: error encountered while handling Zappit API response'
        ];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if the API response indicates a valid input
    return $data;
}
function zappit_get_content_markdown($value)
{
    $api_url = 'https://87b3-49-205-43-13.ngrok-free.app/wp/getContentMarkdown'; // Replace with your actual API endpoint

    // Set up the arguments for the POST request
    $args = [
        'body' => json_encode($value), // Add more data if necessary
        'headers' => [
            'Referer' => home_url(),
            'Content-Type' => 'application/json', // Specify JSON format
        ],
        'method' => 'POST',
    ];

    // Send the POST request
    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        // Handle the error appropriately (log it, display an error message, etc.)
        return [
            'success' => false,
            'message' => 'WP Error: error encountered while handling Zappit API response'
        ];
    }

    $body = wp_remote_retrieve_body($response);
    return $body;
}

// Settings page
function zappit_ai_page()
{
    // Save the custom fields if the form is submitted
    if (isset($_POST['zappit_ai_nonce']) && isset($_POST['zappit_ai_api_key']) && wp_verify_nonce($_POST['zappit_ai_nonce'], 'zappit_ai_save')) {
        // update_option('zappit_ai_meta_title', sanitize_text_field($_POST['zappit_ai_meta_title']));
        // update_option('zappit_ai_meta_description', sanitize_text_field($_POST['zappit_ai_meta_description']));
        // update_option('zappit_ai_meta_keywords', sanitize_text_field($_POST['zappit_ai_meta_keywords']));
        // update_option('zappit_ai_meta_og_image', sanitize_text_field($_POST['zappit_ai_meta_og_image']));
        update_option('zappit_ai_api_key', sanitize_text_field($_POST['zappit_ai_api_key']));

        $input_value = sanitize_text_field($_POST['zappit_ai_api_key']);
        $zappit_api_response = zappit_wp_api_validate($input_value);

        if ($zappit_api_response["valid"]) {
            // Save the option if valid
            update_option('zappit_ai_api_key', $input_value);
            update_option('zappit_ai_company_name', $zappit_api_response["pluginData"]["companyName"]);
            update_option('zappit_ai_company_logo_url', $zappit_api_response["pluginData"]["companyLogoUrl"]);
            update_option('zappit_ai_plugin_name', $zappit_api_response["pluginData"]["wpPluginWhiteLabel"]["pluginName"]);
            update_option('zappit_ai_plugin_author', $zappit_api_response["pluginData"]["wpPluginWhiteLabel"]["author"]);
            update_option('zappit_ai_plugin_author_uri', $zappit_api_response["pluginData"]["wpPluginWhiteLabel"]["authorUri"]);
            update_option('zappit_ai_plugin_description', $zappit_api_response["pluginData"]["wpPluginWhiteLabel"]["description"]);
            echo '<script type="text/javascript">';
            echo '   window.location.replace("' . admin_url('admin.php?page=' . create_slug($zappit_api_response["pluginData"]["companyName"]) . "&success=true") . '")';
            echo '</script>';
            add_filter('all_plugins', 'change_plugin_display_name');
            // exit();
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Invalid API Key. Please try again.</p></div>';
        }
    }

    if ($_GET['success'] == 'true') {
        echo '<div class="notice notice-success is-dismissible"><p>API Key saved successfully.</p></div>';
    }

    // Get the current values of the fields
    $meta_title = get_option('zappit_ai_meta_title', '');
    $meta_desc = get_option('zappit_ai_meta_description', '');
    $meta_keywords = get_option('zappit_ai_meta_keywords', '');
    $meta_og_img = get_option('zappit_ai_meta_og_image', '');
    $api_key = get_option('zappit_ai_api_key', '');
    ?>

    <div class="wrap">
        <h1><img src="<?php echo esc_url(get_option('zappit_ai_company_logo_url')); ?>" alt="Logo"
                style="max-height: 50px; vertical-align: middle; margin-right: 10px;"><?php echo get_option('zappit_ai_company_name') ?>
            - SEO Dashboard</h1>
        <form method="post" action="">
            <?php wp_nonce_field('zappit_ai_save', 'zappit_ai_nonce'); ?>
            <table class="form-table">
                <!-- <tr>
                    <th scope="row"><label for="zappit_ai_meta_title">Meta Title</label></th>
                    <td><input type="text" name="zappit_ai_meta_title" id="zappit_ai_meta_title" value="<?php echo esc_attr($meta_title); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="zappit_ai_meta_description">Meta Description</label></th>
                    <td><input type="text" name="zappit_ai_meta_description" id="zappit_ai_meta_description" value="<?php echo esc_attr($meta_desc); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="zappit_ai_meta_keywords">Meta Keywords</label></th>
                    <td><input type="text" name="zappit_ai_meta_keywords" id="zappit_ai_meta_keywords" value="<?php echo esc_attr($meta_keywords); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="zappit_ai_meta_og_image">Meta Image</label></th>
                    <td><input type="text" name="zappit_ai_meta_og_image" id="zappit_ai_meta_og_image" value="<?php echo esc_attr($meta_og_img); ?>" class="regular-text"></td>
                </tr> -->
                <tr>
                    <th><label for="zappit_ai_api_key">
                            <h1><?php echo get_option('zappit_ai_company_name') ?> SEO API Key</h1>
                        </label></th>
                    <td>
                        <input type="text" id="zappit_ai_api_key" name="zappit_ai_api_key"
                            value="<?php echo esc_attr($api_key); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
            </p>
        </form>
    </div>
    <?php
}

add_action('admin_post_nopriv_submit', 'zappit_ai_page'); // Change 'admin_post_' to 'admin_post_nopriv_' for non-logged-in users.

// Hook to add a submenu page
add_action('admin_menu', 'zappit_ai_add_dashboard_page');
function zappit_ai_add_dashboard_page()
{
    $companyName = get_option('zappit_ai_company_name');
    $companyTagline = get_option('zappit_ai_company_tagline');
    $menuPageTitle = ($companyTagline) ? $companyName . ' | ' . $companyTagline : $companyName;
    add_submenu_page(
        create_slug($companyName),  // Parent menu slug (replace with the existing plugin's slug)
        $menuPageTitle,    // Page title
        'Dashboard',    // Menu title
        'manage_options',        // Capability required
        create_slug($companyName . ' Dashboard'),    // Submenu slug
        'my_plugin_display_posts' // Callback function to display content
    );
}

// Callback function to display the content of the submenu page

// --------------------------------------------- Naman Working Here ---------------------------------------------
function my_plugin_display_posts()
{
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Display the main container to use more width -->
    <div class="p-6 bg-white rounded-lg shadow-lg w-full max-w-full mx-auto mt-8">

    <!-- Header -->
    <h2 class="text-2xl font-semibold text-gray-800 mb-5 inline-flex">
        <img src="<?php echo esc_url(get_option('zappit_ai_company_logo_url')); ?>" alt="Logo" style="max-height: 50px; vertical-align: middle; margin-right: 10px;">
        <?php echo get_option('zappit_ai_company_name') ?> - Dashboard
    </h2>

    <!-- Tabs for Content, Technical, and Page Fixes -->
    <div class="mb-6 border-b border-gray-300">
    <nav class="flex justify-start space-x-6 w-full" role="tablist">

    <!-- Content Tab Button -->
    <button class="tab-button text-[#667085] font-medium px-6 py-3 hover:text-blue-600 focus:text-blue-600 focus:outline-none focus:border-b-2 focus:border-blue-600 transition-colors duration-200 w-auto" role="tab" aria-selected="true" data-tab="content">Content</button>

    <!-- Technical Tab Button -->
    <button class="tab-button text-[#667085] font-medium px-6 py-3 hover:text-blue-600 focus:text-blue-600 focus:outline-none focus:border-b-2 focus:border-blue-600 transition-colors duration-200 w-auto" role="tab" aria-selected="false" data-tab="technical">Technical</button>

    <!-- Page Fixes Tab Button -->
    <button class="tab-button text-[#667085] font-medium px-6 py-3 hover:text-blue-600 focus:text-blue-600 focus:outline-none focus:border-b-2 focus:border-blue-600 transition-colors duration-200 w-auto" role="tab" aria-selected="false" data-tab="page-fixes">Page Fixes</button>

    </nav>
    </div>

    <!-- Content Tab Content -->
    <div id="content-tab" class="tab-content hidden" role="tabpanel" aria-labelledby="content-tab">
    <?php display_table('ai-generated'); ?> <!-- Display content-related posts -->
    </div>

    <!-- Technical Tab Content -->
    <div id="technical-tab" class="tab-content hidden" role="tabpanel" aria-labelledby="technical-tab">
    <?php display_technical_table('technical-post'); ?> <!-- Display technical-related posts -->
    </div>

    <!-- Page Fixes Tab Content -->
    <div id="page-fixes-tab" class="tab-content hidden" role="tabpanel" aria-labelledby="page-fixes-tab">
    <?php display_page_fixes_table('page-fix-post'); ?> <!-- Display page-fix-related posts -->
    </div>

    </div> <!-- End of main container -->

    <!-- JavaScript to retain active tab on refresh -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".tab-button");
        const tabContents = document.querySelectorAll(".tab-content");

        // Check localStorage for the last active tab
        const activeTab = localStorage.getItem("activeTab") || "content";

        // Activate the stored tab
        document.querySelector(`[data-tab="${activeTab}"]`).classList.add("text-blue-600", "border-b-2", "border-blue-600");
        document.getElementById(activeTab + "-tab").classList.remove("hidden");

        // Add event listeners to tabs
        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove("text-blue-600", "border-blue-600", "border-b-2"));
                // Add active class to the clicked tab
                this.classList.add("text-blue-600", "border-b-2", "border-blue-600");

                // Hide all tab contents
                tabContents.forEach(content => content.classList.add("hidden"));
                // Show the corresponding content based on clicked tab
                const tabId = this.getAttribute("data-tab");
                document.getElementById(tabId + "-tab").classList.remove("hidden");

                // Store the active tab in localStorage
                localStorage.setItem("activeTab", tabId);
            });
        });
    });
    </script>
    <?php
}

function check_if_reco_already_exists($reco_id) {
    // $args = [
    //     'post_type'      => 'any', // Specify post type if needed, e.g., 'post', 'page', or a custom post type
    //     'post_status'    => 'any', // Look in all statuses
    //     'meta_query'     => [
    //         [
    //             'key'   => 'zappit_ai_reco_id',
    //             'value' => $meta_value,
    //             'compare' => '=' // Exact match
    //         ]
    //     ],
    //     'posts_per_page' => 1, // Limit results to 1 for performance
    //     'fields'         => 'ids' // Only fetch post IDs
    // ];

    // $query = new WP_Query($args);

    // Check if any posts are found
    // if ($query->have_posts()) {
    //     return true; // Return the post ID of the first match
    // }

    global $wpdb;

    // Define your table name
    $table_name = $wpdb->prefix . 'zappit_ai_new_content'; // Replace with your table name

    // Check if the row exists
    $row_exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE reco_id = %s",
            $reco_id
        )
    );

    if ($row_exists) {
        return true;
    } else {
        return false; // No matching post found
    }
}

function zappit_create_draft_post($title, $content, $doc_id, $meta_desc, $meta_title, $meta_keywords) {
    global $wpdb;

    require_once 'Parsedown.php'; // Include Parsedown
    $parsedown = new Parsedown();

    // Define the post data
    $post_data = [
        'post_title'   => $title,  // Title of the post
        'post_content' => $parsedown->text(wp_kses_post($content)), // Content
        'post_status'  => 'draft',             // Status: draft
        'post_type'    => 'post',              // Post type: post, page, or custom type
        'post_author'  => 1                    // Author ID (ensure this ID exists)
    ];

    // Insert the post into the database
    $post_id = wp_insert_post($post_data);
    // echo 'POST CREATED: ' . $post_id;

    // Check if the post was created successfully
    if ($post_id && !is_wp_error($post_id)) {
        // Define your table name
        $table_name = $wpdb->prefix . 'zappit_ai_new_content'; // Replace 'your_table_name' with your actual table name

        // Define the data to insert
        $data = array(
            'post_id' => $post_id, // Unique post ID
            'reco_id' => $doc_id,
            'title' => $title,
            'description' => $meta_desc,
            'keywords' => $meta_keywords,
            'og_image' => '',
            'status' => 'draft',
        );

        // Insert the row
        $inserted = $wpdb->insert(
            $table_name, // Table name
            $data        // Data to insert
        );

        if (!$inserted) {
            echo "FAILED TO INSERT DATA TO $table_name";
            return new WP_Error('table_insertion_failed', "Failed to insert data to table '$table_name'", array('status' => 500));
        }

        // Check if the insert was successful
        // if ($inserted) {
        //     echo 'Row inserted successfully with ID: ' . esc_html($wpdb->insert_id);
        // } else {
        //     echo 'Failed to insert row: ' . esc_html($wpdb->last_error);
        // }

        // echo "Draft post created successfully with ID: " . $post_id;
        // update_post_meta($post_id, 'zappit_ai_meta_title', sanitize_text_field($meta_title));
        // update_post_meta($post_id, 'zappit_ai_meta_description', sanitize_text_field($meta_desc));
        // update_post_meta($post_id, 'zappit_ai_reco_id', sanitize_text_field($doc_id));
        // update_post_meta($post_id, 'ai-generated', 'true');
        // update_post_meta($post_id, 'zappit_ai_meta_keywords', sanitize_text_field($meta_title));
        // update_post_meta($post_id, 'zappit_ai_meta_og_image', sanitize_text_field());
        return null;
    } else {
        return new WP_Error('post_creation_failed', 'Failed to create post', array('status' => 500));
    }
}

add_action('wp_ajax_toggle_publish_status', 'toggle_publish_status');
function toggle_publish_status() {
    // Verify user permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    // Get POST data
    $post_id = intval($_POST['post_id']);
    $reco_id = strval($_POST['reco_id']);
    $current_action = sanitize_text_field($_POST['current_action']);

    if (!$post_id) {
        wp_send_json_error(['message' => 'Invalid post ID']);
    }

    // Toggle post status
    $new_status = ($current_action === 'Publish') ? 'publish' : 'draft';

    $updated = wp_update_post([
        'ID'          => $post_id,
        'post_status' => $new_status,
    ]);

    if (is_wp_error($updated)) {
        wp_send_json_error(['message' => 'Failed to update post status']);
    } else {
        wp_send_json_success(['message' => 'Post status updated successfully']);
    }
}

function on_any_post_status_change($new_status, $old_status, $post) {
    if ($new_status !== $old_status) {
        global $wpdb;
        // Define your table name
        $table_name = $wpdb->prefix . 'zappit_ai_new_content'; // Replace with your table name

        // Check if the row exists
        $row_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE post_id = %s",
                $post->ID
            )
        );

        if ($row_exists) {
            // Update the row
            $updated = $wpdb->update(
                $table_name, // Table name
                [ 'status' => $new_status ],       // Data to update
                [ 'post_id' => $post->ID ]       // WHERE clause
            );

            // Check if the update was successful
            if ($updated === false) {
                return new WP_Error('zappit_newcontent_status_update_failed', 'Failed to update post status', array('status' => 500));
            }
        }
    }
}
add_action('transition_post_status', 'on_any_post_status_change', 10, 3);

function do_task_one($callback = null) {
    $resp = zappit_get_new_content_actions('AAxRZKJbrEFiSDTpvY1LG628N1y9GM0e7');

    foreach ($resp['newContent'] as $index => $content) {
        $doc_id = $content['doc_id'];
        
        if (check_if_reco_already_exists($doc_id)) {
            continue;
        }

        $p_title = $content['title'];
        $p_m_title = $content['metaTitle'];
        $p_m_desc = $content['metaTag'];
        $post_content_node = $content['content'];
        $clustered_keywords_array = [];

        foreach ($content['clusteredKeywordsGroups'] as $index1 => $obj) {
            array_push($clustered_keywords_array, implode(', ', $obj['clusteredKeywords']));
        }

        $p_m_keywords = implode(', ', $clustered_keywords_array);
        $content_resp = zappit_get_content_markdown($post_content_node);
        $create_draft_resp = zappit_create_draft_post($p_title, $content_resp, $doc_id, $p_m_desc, $p_m_title, $p_m_keywords);
    }
    
    // Execute callback if provided
    if (is_callable($callback)) {
        call_user_func($callback);
    }
}

function do_task_two() {
    // Toast container with unique ID
    echo '<div id="toast-container-ai-generated" class="fixed bottom-5 right-5 space-y-4 z-50"></div>';

    echo '<div class="overflow-x-auto relative">';
    echo '<table class="min-w-full bg-white rounded-lg border border-gray-200">';
    echo '<thead class="sticky top-0 bg-gray-100 border-b">';
    echo '<tr>';
    echo '<th class="text-left text-sm font-medium text-[#667085] px-6 py-4">Topics</th>';
    echo '<th class="text-center text-sm font-medium text-[#667085] px-6 py-4">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    global $wpdb;

    // Define your table name
    $table_name = $wpdb->prefix . 'zappit_ai_new_content'; // Replace 'your_table_name' with your actual table name

    // Fetch all rows
    $rows = $wpdb->get_results("SELECT * FROM $table_name");

    // Check if rows are returned
    if (!empty($rows)) {
        foreach ($rows as $row) {
            $post_id = esc_html($row->post_id);
            $reco_id = esc_html($row->reco_id);
            $post_obj = get_post($post_id);
            $post_status = $post_obj->post_status;

            $action_button_label = $post_status === 'publish' ? 'Unpublish' : 'Publish';
            $action_button_color = $post_status === 'publish'
                ? 'border border-gray-300 text-black px-4 py-2 rounded-md text-sm hover:bg-gray-100 hover:text-black hover:border-gray-400'
                : 'bg-[#1E6FF1] text-white px-7 py-2 rounded-md text-sm hover:bg-blue-600';

            echo '<tr class="bg-white border-b hover:bg-[#F3F7FD] transition-colors duration-300">';
            // ' . esc_html($post_title) . ' for dynamic title
            echo '<td class="px-6 py-4 text-sm text-gray-800">' . $post_obj->post_title . '</td>';
            echo '<td class="px-6 py-4">';
            echo '<div class="flex justify-center space-x-2">';

            // Publish/Unpublish Button with unique data-table-id
            echo '<button 
                    class="' . esc_attr($action_button_color) . '" 
                    data-post-id="' . esc_attr($post_id) . '"
                    data-reco-id="' . esc_attr($reco_id) . '" 
                    data-action="' . esc_attr($action_button_label) . '" 
                    data-table-id="ai-generated">
                    ' . esc_html($action_button_label) . '
                  </button>';

            // Edit Button
            echo '<a href="' . esc_url(get_edit_post_link($post_id)) . '" target="_blank" class="border border-blue-500 text-blue-500 px-6 py-2 rounded-lg text-sm hover:bg-blue-100">Edit</a>';

            // Preview Button
            echo '<a href="' . esc_url(get_permalink($post_id)) . '" target="_blank" class="border border-blue-500 text-blue-500 px-5 py-2 rounded-lg text-sm hover:bg-blue-100">Preview</a>';

            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="2" class="text-center py-4 text-gray-500">No posts found.</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    wp_reset_postdata();
    
    // Inline JavaScript with unique ID handling
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("button[data-action]").forEach(function (button) {
                button.addEventListener("click", function () {
                    const postId = this.getAttribute("data-post-id");
                    const recoId = this.getAttribute("data-reco-id");
                    const action = this.getAttribute("data-action");
                    const tableId = this.getAttribute("data-table-id");
                    const button = this;

                    // Make an AJAX request to publish/unpublish the post
                    fetch(ajaxurl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: new URLSearchParams({
                            action: "toggle_publish_status",
                            post_id: postId,
                            reco_id: recoId,
                            current_action: action,
                        }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                if (action === "Publish") {
                                    button.textContent = "Unpublish";
                                    button.setAttribute("data-action", "Unpublish");
                                    button.classList.remove("bg-[#1E6FF1]", "text-white", "hover:bg-blue-600");
                                    button.classList.add("border", "border-gray-300", "text-black", "hover:bg-gray-100", "hover:text-black", "hover:border-gray-400");
                                    showToast(tableId, "success", "Post published successfully!");
                                } else {
                                    button.textContent = "Publish";
                                    button.setAttribute("data-action", "Publish");
                                    button.classList.remove("border", "border-gray-300", "text-black", "hover:bg-gray-100", "hover:text-black", "hover:border-gray-400");
                                    button.classList.add("bg-[#1E6FF1]", "text-white", "hover:bg-blue-600");
                                    showToast(tableId, "warning", "Post unpublished successfully!");
                                }
                            } else {
                                showToast(tableId, "danger", "Failed to update post status.");
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            showToast(tableId, "danger", "An error occurred. Please try again.");
                        });
                });
            });

            // Show toast function with table ID
            function showToast(tableId, type, message) {
                const toastContainer = document.getElementById("toast-container-" + tableId);

                let toastHTML = "";
                if (type === "success") {
                    toastHTML = `<div class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow" role="alert">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                            </svg>
                        </div>
                        <div class="ms-3 text-sm font-normal">${message}</div>
                    </div>`;
                } else if (type === "danger") {
                    toastHTML = `<div class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow" role="alert">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                            </svg>
                        </div>
                        <div class="ms-3 text-sm font-normal">${message}</div>
                    </div>`;
                } else if (type === "warning") {
                    toastHTML = `<div class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow" role="alert">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                            </svg>
                        </div>
                        <div class="ms-3 text-sm font-normal">${message}</div>
                    </div>`;
                }

                const toast = document.createElement("div");
                toast.innerHTML = toastHTML;
                toastContainer.appendChild(toast);

                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });
    </script>
    <?php
}

/**
 * Helper function to display a generic table for the "Content" tab
 */
function display_table($meta_key)
{
    do_task_one('do_task_two');
}

/**
 * Helper function to display the "Technical" table
 */
function display_technical_table($meta_key)
{
    global $plugin_assets_folder;
    ?> 
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-50 rounded-lg border border-gray-200">
            <thead class="sticky top-0 bg-gray-100 border-b">
                <tr>
                    <th class="text-left text-sm font-medium text-[#667085] px-6 py-4">Page ID</th>
                    <th class="text-left text-sm font-medium text-[#667085] px-6 py-4">Error</th>
                    <th class="text-center text-sm font-medium text-[#667085] px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody id="data-container">
    <?php
    $techActions = zappit_get_technical_actions('AAxRZKJbrEFiSDTpvY1LG628N1y9GM0e7');

    foreach ($techActions["technical"] as $index => $action) {
        $action_page_id = $action["page_id"];
        $action_url = $action["url"];
        $action_desc = $action["desc"];
        $action_desc_slug = create_slug_($action_desc);
        $action_type = $action["recommendationType"];
        $action_completed = $action["markAsCompleted"] ? "Completed" : "Not Completed";
        $drawer_id = "technicalDrawer-" . $action_page_id . '-' . $action_desc_slug;

        ?>
                
    <?php } ?>
            </tbody>
        </table>

        <div id="pagination-controls" class="flex items-center gap-4 p-4 bg-gray-100 rounded-lg shadow-md">
    <!-- Previous Button -->
    <button id="prev-btn" class="bg-blue-500 text-white font-semibold py-1 px-2 rounded-lg hover:bg-blue-600 transition-colors duration-300 disabled:bg-gray-300 disabled:cursor-not-allowed text-sm" disabled>
        Previous
    </button>

    <!-- Page Info -->
    <span id="page-info" class="text-sm font-medium text-gray-700">Page 1</span>

    <!-- Next Button -->
    <button id="next-btn" class="bg-blue-500 text-white font-semibold py-1 px-2 rounded-lg hover:bg-blue-600 transition-colors duration-300 disabled:bg-gray-300 disabled:cursor-not-allowed text-sm">
        Next
    </button>
</div>



    <script>
class ServerSidePagination {
  constructor(config) {
    // Configuration parameters
    this.apiUrl = config.apiUrl;
    this.bearerToken = config.bearerToken;
    this.domainId = config.domainId;
    this.recommendationType = config.recommendationType;
    this.lastCreatedAt = config.lastCreatedAt;
    this.pageData = {};
    this.items = [];

    // Pagination state
    this.currentPage = 1;
    this.pageSize = config.pageSize || 2; // Show 2 rows per page

    // DOM elements
    this.containerElement = config.containerElement;
    this.paginationElement = config.paginationElement;
    this.prevButton = this.paginationElement.querySelector('#prev-btn');
    this.nextButton = this.paginationElement.querySelector('#next-btn');
    this.pageInfoElement = this.paginationElement.querySelector('#page-info');

    // Bind methods
    this.fetchData = this.fetchData.bind(this);
    this.renderData = this.renderData.bind(this);
    this.updatePaginationControls = this.updatePaginationControls.bind(this);

    // Add event listeners to buttons
    this.prevButton.addEventListener('click', () => this.goToPreviousPage());
    this.nextButton.addEventListener('click', () => this.goToNextPage());
  }

  async fetchData() {
    try {
        // Show loading state
        this.containerElement.innerHTML = '<p>Loading...</p>';

        // Prepare request payload
        const payload = {
            domainId: this.domainId,
            recommendationType: this.recommendationType,
            page: this.currentPage, // Current page number
            pageSize: this.pageSize, // Number of rows per page
            lastCreatedAt: this.lastCreatedAt || null, // Include lastCreatedAt if available
            loadMore: this.lastCreatedAt ? true : false,
        };

        console.log('Request Payload:', payload);
        
        // Fetch data from API
        const response = await fetch(this.apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${this.bearerToken}`,
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        console.log('API Response:', data);

        // Extract technical category data
        const items = data.technical || [];
        const hasMore = data.hasMore?.technical || false;

        // Save page data
        this.pageData[this.currentPage] = items;
        this.items = items; // Update the current page items for rendering

        // Save the lastCreatedAt for the next payload
        if (hasMore) {
            this.lastCreatedAt = data.lastCreatedAt;
        }

        // Render current page data and update pagination controls
        this.renderData(this.items);
        this.updatePaginationControls(hasMore);

        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        this.containerElement.innerHTML = `<p>Error loading data: ${error.message}</p>`;
        this.updatePaginationControls(false);
    }
}



renderData(items) {
                // Clear previous content
                this.containerElement.innerHTML = '';

                // Handle empty items
                if (!items || items.length === 0) {
                    this.containerElement.innerHTML = '<p>No items found.</p>';
                    return;
                }

                // Render each item (only for the current page)
                items.forEach(item => {
                    const rowHTML = `
                        <tr class=" border-t border-gray-200 hover:bg-[#F3F7FD] transition-colors duration-300">
                            <td class="px-6 py-4 text-sm text-gray-800">${item.id}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">${item.desc}</td>
                            
                        </tr>
                    `;

                    this.containerElement.innerHTML += rowHTML;  // Append each row to the table body
                });
            }




  updatePaginationControls(hasMore) {
    // Update page info
    this.pageInfoElement.textContent = `Page ${this.currentPage}`;

    // Update previous button
    this.prevButton.disabled = this.currentPage === 1;

    // Update next button
    this.nextButton.disabled = !hasMore;
  }

  // Navigate to the previous page
goToPreviousPage() {
    if (this.currentPage > 1) {
        this.currentPage--;
        this.items = this.pageData[this.currentPage]; // Load items from the stored state
        this.renderData(this.items);
        this.updatePaginationControls(true); // Update controls accordingly
    }
}

// Navigate to the next page
goToNextPage() {
    this.currentPage++;
    if (this.pageData[this.currentPage]) {
        // Load from stored state if already fetched
        this.items = this.pageData[this.currentPage];
        this.renderData(this.items);
        this.updatePaginationControls(true);
    } else {
        // Fetch data if not already fetched
        this.fetchData();
    }
}

  // Initialize pagination
  init() {
    this.fetchData();
  }
}

// Example usage
document.addEventListener('DOMContentLoaded', () => {
    const paginationInstance = new ServerSidePagination({
        apiUrl: 'https://onelink-developer.spotlightapis.com/getZappitActions',
        bearerToken: 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjNmZDA3MmRmYTM4MDU2NzlmMTZmZTQxNzM4YzJhM2FkM2Y5MGIyMTQiLCJ0eXAiOiJKV1QifQ.eyJuYW1lIjoiQiBUcmFjayIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NKUmJ2b05OWGd6dWVmSU81VE13V2ptUjUwa1V0SWFObWtzT1k3OGVlN3U4RHRRVDR3PXM5Ni1jIiwiaXNzIjoiaHR0cHM6Ly9zZWN1cmV0b2tlbi5nb29nbGUuY29tL29uZWxpbmstZGV2ZWxvcGVyIiwiYXVkIjoib25lbGluay1kZXZlbG9wZXIiLCJhdXRoX3RpbWUiOjE3MzI0MzI3NDAsInVzZXJfaWQiOiJqOVFtTFFPZVU1TjR4MDBDbExuNjBHYnRtVVkyIiwic3ViIjoiajlRbUxRT2VVNU40eDAwQ2xMbjYwR2J0bVVZMiIsImlhdCI6MTczMjk2OTI3OSwiZXhwIjoxNzMyOTcyODc5LCJlbWFpbCI6ImJ0cmFjay5tYWluQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJmaXJlYmFzZSI6eyJpZGVudGl0aWVzIjp7Imdvb2dsZS5jb20iOlsiMTA1MTk3MDgwMTIzMzQ1MjEwNjc1Il0sImVtYWlsIjpbImJ0cmFjay5tYWluQGdtYWlsLmNvbSJdfSwic2lnbl9pbl9wcm92aWRlciI6Imdvb2dsZS5jb20ifX0.Kd1pHv16y4e_DPLsuGOO6vrNjf88riFJt-rOaqOEJpf64wMH0QkzcFDrGfWeNor6yeIBUkj4KBHXEfm1s4NXCOR_Dk3A55a--KpImJjQEjcv3bso1UxTsM5mV_nQGO83UHkfA91nPK85vVgspvgX2Aoq2hcs-Yn5q5Ko7k88Np9xlVs1YCMms7ZURZLkBk-billrQ86iL_M7aHP5L1RQXC1hW8AEPrHKg-NKp8vnN_YB-qjMMBcFlR2qNdE1SOdaPiOWwtKUxkJSNcU0IZn6r2eo2oCIuYrqHaPdcYFXm0MBDyNbT7I7nwiT15RfDYl1tIBwObSYVoNjHvrqyq8gmA',
        domainId: 'zappit.ai',
        recommendationType: 'ALL',
        containerElement: document.getElementById('data-container'),
        paginationElement: document.getElementById('pagination-controls'),
        pageSize: 5 // Limit to 2 rows per page
    });

    paginationInstance.init();
});


    </script>

    </div>
    
    <?php
    // Reset the post data after the loop
    wp_reset_postdata();
}

/**
 * Helper function to display the "Page Fixes" table
 */
function display_page_fixes_table($meta_key)
{
    global $plugin_assets_folder;
    ?>
  <div class="overflow-x-auto">
    <table class="min-w-full bg-[#F9FAFB] rounded-lg border border-gray-200">
        <thead class="bg-[#F4F4F4]">
            <tr>
                <th class="text-left text-sm font-medium text-[#667085] px-6 py-4">Topics</th>
                <th class="text-center text-sm font-medium text-[#667085] px-6 py-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pageActions = zappit_get_page_fixes_actions('AAxRZKJbrEFiSDTpvY1LG628N1y9GM0e7');

            foreach ($pageActions["pageFixes"] as $index => $action) {
                $action_page_id = $action["page_id"];
                $action_url = $action["url"];
                $action_desc = $action["desc"];
                $action_desc_slug = create_slug_($action_desc);
                $action_type = isset($action["type"]) ? $action["type"] : 'N/A'; // Default to 'N/A' if not set
                $action_completed = isset($action["completed"]) ? $action["completed"] : 'Pending'; // Default to 'Pending' if not set
                $drawer_id = "drawer-" . $action_page_id . '-' . $action_desc_slug;
                ?>

                <tr  class="bg-white border-t border-gray-200 hover:bg-[#F3F7FD] transition-colors duration-300">
                    <td class="px-6 py-4 text-sm text-[#1D1D1D]"><?php echo $action_desc; ?></td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-4">
                            <!-- View Details Button -->
                            <button data-drawer="<?php echo $drawer_id; ?>" class="open-drawer-btn border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-sm hover:bg-blue-100 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Drawer -->
                <div id="<?php echo $drawer_id; ?>" class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl p-6 relative lg:ml-[8rem] h-[90vh] mt-5 overflow-auto">
                        <!-- Back Button -->
                        <button data-drawer="<?php echo $drawer_id; ?>" class="close-drawer-btn absolute top-4 left-4 text-gray-600 hover:text-gray-800 flex items-center ml-2 mt-1">
                            <img src="<?php echo $plugin_assets_folder; ?>/images/arrow_back.png" alt="" class="w-5 h-5">
                        </button>

                        <!-- Content -->
                        <div class="px-9 py-4 flex flex-col">
                            <h2 class="text-xl font-bold text-[#1D1D1D] mb-4 mt-1"><?php echo $action_desc; ?></h2>
                            <h4 class="text-lg font-medium text-[#1D1D1D] mb-4">Information</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <strong class="w-32 text-[#717171] text-sm font-medium">URL:</strong>
                                    <a href="<?php echo $action_url; ?>" class="text-blue-500 underline text-sm"><?php echo $action_url; ?></a>
                                </div>
                                <div class="flex items-center">
                                    <strong class="w-32 text-[#717171] text-sm font-medium">Type:</strong>
                                    <span class="bg-blue-100 text-blue-500 px-2 rounded-2xl text-sm flex items-center gap-2 font-medium">
                                        <div class="rounded-full h-2 w-2 bg-blue-700"></div> <?php echo $action_type; ?>
                                    </span>
                                </div>
                                <div class="flex items-center">
    <strong class="w-32 text-[#717171] text-sm font-medium">Description:</strong>
    <span class="text-sm font-bold"><?php echo $action_desc; ?></span>
  </div>
                                <div class="flex items-center">
                                    <strong class="w-32 text-[#717171] text-sm font-medium">Status:</strong>
                                    <span class="font-bold text-sm"><?php echo $action_completed; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="border-2 rounded-xl m-4">         
    
                <!-- Right Section -->
                <div class="flex-1 p-4 ">
    <div class="mb-4">
        <h4 class="text-base font-bold text-[#1D1D1D] mb-2">Meta Tag</h4>
        <div class="flex items-center justify-between p-3 rounded-lg">
            <!-- Before -->
            <div class="flex-1">
                <p class="text-xs font-medium text-black mb-1">Before</p>
                <code class="block text-black border border-gray-300 rounded p-1 py-3 text-[0.5rem] font-mono bg-[#F7F9FB]">
                    &lt;meta name="description" content="<span class="text-blue-600">Welcome to our website. We offer great services and products.</span>"&gt; 
                </code>
            </div>
            <!-- Arrow -->
            <div class="mx-3 ">
                <img src="<?php echo $plugin_assets_folder ?>/images/arrow_right.png" alt="Arrow" class="w-6 h-2 mt-4">
            </div>
            <!-- After -->

            <div class="flex-1">
    <p class="text-xs font-medium text-black mb-1">After</p>
    
    <div class="flex items-center justify-between">
    <code id="metaTagContent" class="flex text-black border border-gray-300 rounded p-1 py-3 text-[0.5rem] font-mono bg-[#F7F9FB]">
        <span>
            &lt;meta name="description" content="<span class="text-blue-600">Discover top-quality gaming products, reviews, and expert tips at Gameshop. Boost your gaming experience with the best deals.</span>"&gt;
        </span>
        <span id="copyButton" class="flex justify-end items-center h-5 w-8 pr-1 cursor-pointer">
            <img id="copyIcon" src="<?php echo $plugin_assets_folder ?>/images/copy.png" alt="Copy">
        </span>
    </code>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const copyButton = document.getElementById("copyButton");
        const metaTagContent = document.getElementById("metaTagContent");
        const copyIcon = document.getElementById("copyIcon");

        copyButton.addEventListener("click", function () {
            // Copy the text content of the code element
            navigator.clipboard.writeText(metaTagContent.textContent.trim())
                .then(() => {
                    // Change icon to tick
                    copyIcon.src = "/wp-content/plugins/zappit_-1/assets/images/copy check.png";
                    
                    // Revert icon after 2 seconds
                    setTimeout(() => {
                        copyIcon.src = "<?php echo $plugin_assets_folder ?>/images/copy.png";
                    }, 3000);
                })
                .catch(err => {
                    console.error("Failed to copy text: ", err);
                });
        });
    });
</script>

            
        </div>
    </div>

    <div>
        <h4 class="text-base font-bold text-[#1D1D1D] mb-2">Title Tag</h4>
        <div class="flex items-center justify-between p-3 rounded-lg">
            <!-- Before -->
            <div class="flex-1">
                <p class="text-xs font-medium text-black mb-1">Before</p>
                <code class="block text-black border border-gray-300 rounded p-1 py-3 text-[0.5rem] font-mono bg-[#F7F9FB]">
                    &lt;title&gt;<span class="text-blue-600">Home</span>&lt;/title&gt;
                </code>
            </div>
            <!-- Arrow -->
            <div class="mx-3">
                <img src="<?php echo $plugin_assets_folder ?>/images/arrow_right.png" alt="Arrow" class="w-6 h-2 mt-4">
            </div>
            <!-- After -->
            <div class="flex-1 ">
    <p class="text-xs font-medium text-black mb-1">After</p>
    
    <code id="titleTagContent" class="flex items-center text-black border border-gray-300 rounded p-1 py-3 text-[0.5rem] font-mono bg-[#F7F9FB]">
        <span style="white-space: nowrap; display: inline-block; width: 100%;">
            &lt;title&gt;<span class="text-blue-600">Gameshop | Best Gaming Products, Reviews & Tips for Gamers</span>&lt;/title&gt;
        </span>

        <span class="flex items-center justify-end h-5 w-full pr-1 cursor-pointer">
            <img id="copyTitleTag" src="<?php echo $plugin_assets_folder ?>/images/copy.png" alt="Copy">
        </span>
    </code>
</div>

<!-- Toaster -->
<div id="toaster" class="hidden fixed top-4 right-4 bg-blue-600 text-white text-sm rounded px-4 py-2 shadow-lg">
    Copied to clipboard!
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const copyButton = document.getElementById("copyTitleTag");
        const titleTagContent = document.getElementById("titleTagContent");
        const toaster = document.getElementById("toaster");

        copyButton.addEventListener("click", function () {
            // Extract the content of the <code> tag
            const contentToCopy = titleTagContent.innerText.trim();
            // Copy to clipboard
            navigator.clipboard.writeText(contentToCopy).then(() => {
                // Show toaster notification
                toaster.classList.remove("hidden");
                setTimeout(() => {
                    toaster.classList.add("hidden");
                }, 2000); // Hide after 2 seconds
            }).catch(err => console.error("Failed to copy text: ", err));
        });
    });
</script>



                    </div>
                </div>
                <?php
            }
            ?>
        </tbody>
    </table>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Open drawer
        document.querySelectorAll(".open-drawer-btn").forEach(button => {
            button.addEventListener("click", () => {
                const drawerId = button.getAttribute("data-drawer");
                const drawer = document.getElementById(drawerId);
                drawer.classList.remove("opacity-0", "pointer-events-none");
            });
        });

        // Close drawer
        document.querySelectorAll(".close-drawer-btn").forEach(button => {
            button.addEventListener("click", () => {
                const drawerId = button.getAttribute("data-drawer");
                const drawer = document.getElementById(drawerId);
                drawer.classList.add("opacity-0", "pointer-events-none");
            });
        });

        // Close drawer on Escape key
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                document.querySelectorAll(".fixed").forEach(drawer => {
                    drawer.classList.add("opacity-0", "pointer-events-none");
                });
            }
        });

        // Close drawer when clicking outside
        document.querySelectorAll(".fixed").forEach(drawer => {
            drawer.addEventListener("click", (event) => {
                drawer.classList.add("opacity-0", "pointer-events-none");
            });

            // Prevent closing when clicking inside the drawer content
            const content = drawer.querySelector(".bg-white");
            if (content) {
                content.addEventListener("click", (event) => {
                    event.stopPropagation();
                });
            }
        });
    });
</script>



    <?php
}





// ------------------------------------------------------------------------------------------------------------------

// Register API routes
add_action('rest_api_init', 'zappit_ai_register_api_routes');

function zappit_ai_register_api_routes()
{
    register_rest_route('zappit-ai/v1', '/add-post', array(
        'methods' => 'POST',
        'callback' => 'zappit_ai_add_post',
        'permission_callback' => 'zappit_ai_api_permissions_check'
    ));

    register_rest_route('zappit-ai/v1', '/ping', array(
        'methods' => 'POST',
        'callback' => 'zappit_ai_ping',
        'permission_callback' => 'zappit_ai_api_permissions_check'
    ));
}

function zappit_ai_api_permissions_check($request)
{
    $api_key = $request->get_header('X-API-Key');
    return $api_key === get_option('zappit_ai_api_key');
}


function zappit_ai_ping($request)
{
    $api_key = $request->get_header('X-API-Key');
    $params = $request->get_json_params();

    $companyNameUpdated = false;
    $companyLogoUrlUpdated = false;

    if (isset($params['companyName'])) {
        $companyNameUpdated = update_option('zappit_ai_company_name', $params['companyName']);
    }
    if (isset($params['companyLogoUrl'])) {
        $companyLogoUrlUpdated = update_option('zappit_ai_company_logo_url', $params['companyLogoUrl']);
    }

    $companyName = get_option('zappit_ai_company_name');
    $companyLogoUrl = get_option('zappit_ai_company_logo_url');

    if ($companyLogoUrlUpdated || $companyNameUpdated) {
        return new WP_REST_Response(['result' => 'success', 'companyName' => $companyName, 'companyLogoUrl' => $companyLogoUrl], 200);
    } else {
        return new WP_Error('option_update_failed', 'Failed to update options', array('status' => 500));
    }
}

function get_post_metadata($post_id)
{
    global $wpdb;

    // Define your table name
    $table_name = $wpdb->prefix . 'zappit_ai_new_content'; // Add your table name after the prefix

    // Fetch the row with the unique post_id
    $row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE post_id = %d LIMIT 1",
            $post_id
        )
    );

    return $row;
}

function hook_meta()
{
    $obj_id = get_queried_object_id();
    $post_metadata = get_post_metadata($obj_id);
    echo "\n" . '<!-- Zappit AI - SEO Tags -->' . "\n\n";
    if (is_home()) {
        echo '<meta name="title" content="' . get_option('zappit_ai_meta_title') . '" />' . "\n";
        echo '<meta property="og:title" content="' . get_option('zappit_ai_meta_title') . '" />' . "\n";
        echo '<meta name="description" content="' . get_option('zappit_ai_meta_description') . '" />' . "\n";
        echo '<meta property="og:description" content="' . get_option('zappit_ai_meta_description') . '" />' . "\n";
        echo '<meta property="og:image" content="' . get_option('zappit_ai_meta_og_image', true) . '" />' . "\n";
        echo '<meta property="keywords" content="' . get_option('zappit_ai_meta_keywords', true) . '" />' . "\n";
    } else {
        // echo '<meta name="title" content="' . get_the_title(get_queried_object_id()) . '" />' . "\n";
        // echo '<meta property="og:title" content="' . get_the_title(get_queried_object_id()) . '" />' . "\n";
        // echo '<meta name="description" content="' . get_post_meta(get_queried_object_id(), 'zappit_ai_meta_description', true) . '" />' . "\n";
        // echo '<meta property="og:description" content="' . get_post_meta(get_queried_object_id(), 'zappit_ai_meta_description', true) . '" />' . "\n";
        // echo '<meta property="og:image" content="' . get_post_meta(get_queried_object_id(), 'zappit_ai_meta_og_image', true) . '" />' . "\n";
        // echo '<meta name="keywords" content="' . get_post_meta(get_queried_object_id(), 'zappit_ai_meta_keywords', true) . '" />' . "\n";

        echo '<meta name="title" content="' . $post_metadata->title . '" />' . "\n";
        echo '<meta property="og:title" content="' . $post_metadata->title . '" />' . "\n";
        echo '<meta name="description" content="' . $post_metadata->description . '" />' . "\n";
        echo '<meta property="og:description" content="' . $post_metadata->description . '" />' . "\n";
        echo '<meta property="og:image" content="' . $post_metadata->og_image . '" />' . "\n";
        echo '<meta name="keywords" content="' . $post_metadata->keywords . '" />' . "\n";
    }

    $post_type = (get_post_type() == 'page') ? 'website' : ((get_post_type() == 'post') ? 'article' : '');

    echo '<meta property="og:type" content="' . $post_type . '" />' . "\n";
    echo '<meta property="og:url" content="' . get_current_page_link() . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . get_option('zappit_ai_meta_title') . '" />' . "\n";

    echo "\n" . '<!-- Zappit AI - SEO Tags End -->' . "\n\n";

}

add_action('wp_head', 'hook_meta');

function zappit_ai_add_post($request)
{
    $params = $request->get_json_params();

    if (!isset($params['topic']) || !isset($params['content'])) {
        return new WP_Error('missing_params', 'Missing required parameters', array('status' => 400));
    }

    $topic = sanitize_text_field($params['topic']);
    $content = wp_kses_post($params['content']);
    $type = $params['type'];

    require_once 'Parsedown.php'; // Include Parsedown
    $parsedown = new Parsedown();

    // Create the post
    $post_data = [
        'post_title' => $topic,
        'post_content' => $parsedown->text($content),
        'post_status' => 'publish',
        'post_author' => 1, // Default to admin user
        'post_type' => $type
    ];

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        update_post_meta($post_id, 'zappit_ai_meta_description', sanitize_text_field($params['metadata']['description']));
        update_post_meta($post_id, 'zappit_ai_meta_keywords', sanitize_text_field($params['metadata']['keywords']));
        update_post_meta($post_id, 'zappit_ai_meta_og_image', sanitize_text_field($params['metadata']['og_image']));
        return new WP_REST_Response(['post_id' => $post_id], 200);
    } else {
        return new WP_Error('post_creation_failed', 'Failed to create post', array('status' => 500));
    }
}

add_filter('all_plugins', 'change_plugin_display_name');
function change_plugin_display_name($plugins)
{
    // Define the path to the main plugin file (relative to the plugins folder)
    $plugin_file = basename(dirname(__FILE__)) . '/' . basename(__FILE__); // Replace with your actual plugin path

    // Check if the plugin exists in the list
    if (isset($plugins[$plugin_file])) {
        $plugins[$plugin_file]['Name'] = get_option('zappit_ai_plugin_name');
        $plugins[$plugin_file]['Description'] = get_option('zappit_ai_plugin_description');
        $plugins[$plugin_file]['Author'] = get_option('zappit_ai_plugin_author');
        $plugins[$plugin_file]['AuthorURI'] = get_option('zappit_ai_plugin_author_uri');
    }

    return $plugins;
}
