<?php

declare( strict_types=1 );

namespace Custom_PTT\Admin;

use Exception;

/**
 * Notices Class
 *
 * This class is responsible for adding admin notices.
 *
 * @package Custom_PTT\Admin
 * @since 0.1.0-alpha
 */
final class Notices {

	/**
	 * Notices constructor.
	 *
	 * @since 0.1.0-alpha
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks display_admin_notices to the admin_notices action.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function init(): void {
		add_action( 'admin_notices', array( Notices::class, 'display_admin_notices' ) );
	}

	/**
	 * Add admin Notices
	 *
	 * @param string $severity
	 * @param string $message
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public static function add_admin_notice( string $severity, string $message ): void {
		$notices   = get_option( 'custom_ptt_notices', array() );
		$notices[] = array(
			'severity' => $severity,
			'message'  => $message,
		);
		update_option( 'custom_ptt_notices', $notices );
	}

	/**
	 * Render admin notices
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public static function display_admin_notices(): void {
		$notices = get_option( 'custom_ptt_notices', array() );

		foreach ( $notices as $notice ) {
			printf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr( $notice['severity'] ), esc_html( $notice['message'] ) );
		}

		delete_option( 'custom_ptt_notices' );
	}
}
