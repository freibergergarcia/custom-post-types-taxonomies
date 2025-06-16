const { chromium, expect } = require('@playwright/test');

/**
 * Global setup for E2E tests.
 *
 * This sets up the WordPress environment and creates an admin user session
 * that can be reused across tests for better performance.
 */
async function globalSetup() {
	const browser = await chromium.launch();
	const context = await browser.newContext();
	const page = await context.newPage();

	console.log('🔧 Starting global setup...');

	// Navigate to WordPress login
	console.log('📍 Navigating to WordPress login...');
	await page.goto('http://localhost:8888/wp-admin');

	// Wait for login form to be visible
	await expect(page.locator('#user_login')).toBeVisible();

	// Login as admin (wp-env default credentials)
	console.log('🔑 Logging in as admin...');
	await page.fill('#user_login', 'admin');
	await page.fill('#user_pass', 'password');
	await page.click('#wp-submit');

	// Wait for successful login by checking for dashboard
	await expect(page.locator('#wpadminbar')).toBeVisible();
	console.log('✅ Login successful');

	// Ensure plugin is activated using WP CLI (more reliable for CI)
	console.log('🔍 Ensuring Custom PTT plugin is activated...');

	try {
		// Use fetch to make a request to wp-admin/admin-ajax.php to run WP CLI command
		// This is a workaround since we can't run wp-cli directly from Playwright

		// Instead, let's check via the admin interface
		await page.goto('http://localhost:8888/wp-admin/plugins.php');

		// Wait for plugins page to load
		await page.waitForSelector('.wp-list-table');

		// Look for our plugin with more flexible selectors
		const pluginRow = page.locator('tr').filter({
			hasText: 'custom-post-types-taxonomies'
		}).or(page.locator('tr').filter({
			hasText: 'Custom Post Types and Taxonomies'
		}));

		if (await pluginRow.first().isVisible()) {
			// Check if it has an "Activate" link (meaning it's not active)
			const activateLink = pluginRow.locator('a').filter({ hasText: 'Activate' });

			if (await activateLink.isVisible()) {
				console.log('🔌 Activating Custom PTT plugin...');
				await activateLink.first().click();

				// Wait for activation and check for success
				await page.waitForSelector('.notice-success, .notice-error', { timeout: 10000 });

				const hasSuccess = await page.locator('.notice-success').isVisible();
				if (hasSuccess) {
					console.log('✅ Plugin activated successfully');
				} else {
					console.log('⚠️ Plugin activation may have failed');
				}
			} else {
				console.log('✅ Plugin already active');
			}
		} else {
			console.log('⚠️ Plugin not found in plugins list');
		}
	} catch (error) {
		console.log('⚠️ Error during plugin activation check:', error.message);
	}

	// Navigate to dashboard to ensure we're in a good state
	await page.goto('http://localhost:8888/wp-admin/');
	await expect(page.locator('#wpadminbar')).toBeVisible();

	// Check if our plugin menu items exist
	const customPttMenu = page.locator('#adminmenu a[href*="custom-post-types-taxonomies"]');
	if (await customPttMenu.first().isVisible()) {
		console.log('✅ Custom PTT admin menu found');

		// Test navigation to make sure the pages work
		await customPttMenu.first().click();
		await expect(page.locator('h1').first()).toBeVisible();
		console.log('✅ Custom PTT pages are accessible');
	} else {
		console.log('⚠️ Custom PTT admin menu not found');
	}

	// Save authenticated state for reuse in tests
	await context.storageState({ path: 'tests/e2e/fixtures/admin-auth-state.json' });

	await browser.close();

	console.log('✅ Global setup completed - Admin session saved');
}

module.exports = globalSetup;
