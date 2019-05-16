<?php
/**
 * Integrate Lexicata and WPForms
 *
 * @package    Integrate_Lexicata_WPForms
 * @since      1.0.0
 * @copyright  Copyright (c) 2019, Bill Erickson
 * @license    GPL-2.0+
 */

class Integrate_Lexicata_WPForms {

    /**
     * Primary Class Constructor
     *
     */
    public function __construct() {

        add_filter( 'wpforms_builder_settings_sections', array( $this, 'settings_section' ), 20, 2 );
        add_filter( 'wpforms_form_settings_panel_content', array( $this, 'settings_section_content' ), 20 );
        add_action( 'wpforms_process_complete', array( $this, 'send_data_to_lexicata' ), 10, 4 );

    }

    /**
     * Add Settings Section
     *
     */
    function settings_section( $sections, $form_data ) {
        $sections['be_lexicata'] = __( 'Lexicata', 'integrate-lexicata-wpforms' );
        return $sections;
    }


    /**
     * Lexicata Settings Content
     *
     */
    function settings_section_content( $instance ) {
        echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-be_lexicata">';
        echo '<div class="wpforms-panel-content-section-title">' . __( 'Lexicata', 'integrate-lexicata-wpforms' ) . '</div>';

        wpforms_panel_field(
            'text',
            'settings',
            'be_lexicata_auth_token',
            $instance->form_data,
            __( 'Lexicata Authorization Token', 'integrate-lexicata-wpforms' )
        );

		wpforms_panel_field(
		    'select',
		    'settings',
		    'be_lexicata_field_first_name',
		    $instance->form_data,
		    __( 'First Name', 'integrate-lexicata-wpforms' ),
		    array(
		        'field_map'   => array( 'text', 'name' ),
		        'placeholder' => __( '-- Select Field --', 'integrate-lexicata-wpforms' ),
		    )
		);

        wpforms_panel_field(
            'select',
            'settings',
            'be_lexicata_field_last_name',
            $instance->form_data,
            __( 'Last Name', 'integrate-lexicata-wpforms' ),
            array(
                'field_map'   => array( 'text', 'name' ),
                'placeholder' => __( '-- Select Field --', 'integrate-lexicata-wpforms' ),
            )
        );

        wpforms_panel_field(
            'select',
            'settings',
            'be_lexicata_field_email',
            $instance->form_data,
            __( 'Email Address', 'integrate-lexicata-wpforms' ),
            array(
                'field_map'   => array( 'email' ),
                'placeholder' => __( '-- Select Field --', 'integrate-lexicata-wpforms' ),
            )
        );

		wpforms_panel_field(
            'select',
            'settings',
            'be_lexicata_field_phone',
            $instance->form_data,
            __( 'Phone Number', 'integrate-lexicata-wpforms' ),
            array(
                'field_map'   => array( 'text', 'phone' ),
                'placeholder' => __( '-- Select Field --', 'integrate-lexicata-wpforms' ),
            )

		);

		wpforms_panel_field(
			'select',
			'settings',
			'be_lexicata_field_message',
			$instance->form_data,
			__( 'Message', 'integrate-lexicata-wpforms' ),
			array(
				'field_map' => array( 'textarea' ),
                'placeholder' => __( '-- Select Field --', 'integrate-lexicata-wpforms' ),
			)
		);

		wpforms_panel_field(
			'select',
			'settings',
			'be_lexicata_field_referring_url',
			$instance->form_data,
			__( 'Referring URL', 'integrate-lexicata-wpforms' ),
			array(
				'field_map' => array( 'text', 'url' ),
                'placeholder' => __( '-- Select Field --', 'integrate-lexicata-wpforms' ),
			)
		);

        echo '</div>';
    }

    /**
     * Integrate WPForms with Lexicata
     *
     */
    function send_data_to_lexicata( $fields, $entry, $form_data, $entry_id ) {

		$auth_token = false;
		if( !empty( $form_data['settings']['be_lexicata_auth_token'] ) )
			$auth_token = esc_html( $form_data['settings']['be_lexicata_auth_token'] );

		if( empty( $auth_token ) )
			return;

		$inbox_lead = array();
		$lexicata_fields = array(
			'from_first' => 'be_lexicata_field_first_name',
			'from_last' => 'be_lexicata_field_last_name',
			'from_message' => 'be_lexicata_field_message',
			'from_email' => 'be_lexicata_field_email',
			'from_phone' => 'be_lexicata_field_phone',
			'referring_url' => 'be_lexicata_field_referring_url',
		);
		foreach( $lexicata_fields as $lexicata_key => $form_setting_key ) {
			$form_field_id = $form_data['settings'][ $form_setting_key ];
			if( !empty( $form_field_id ) && !empty( $fields[ $form_field_id ]['value'] ) )
				$inbox_lead[ $lexica_key ] = $fields[ $form_field_key ]['value'];
		}

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json;'
            ),
            'body' => json_encode( array( 'auth_token' => $auth_token, 'inbox_lead' => $inbox_lead ) )
        );

		// Filter for limiting integration
		// @see https://www.billerickson.net/code/integrate-lexicata-wpforms-conditional-processing/
        if( ! apply_filters( 'be_lexicata_process_form', true, $fields, $form_data ) )
            return;

        // Submit to Lexicata
        $request = wp_remote_post( add_query_arg( $args, 'http://app.lexicata.com/inbox_leads' ) );

    }

}
new Integrate_Lexicata_WPForms;
