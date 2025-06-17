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
	await page.goto('http://localhost:8889/wp-admin');

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

	// Activate plugin using browser automation (CI/CD friendly)
	console.log('🔍 Activating Custom PTT plugin via browser...');

	try {
		// Navigate to plugins page
		await page.goto('http://localhost:8889/wp-admin/plugins.php');

		// Wait for plugins page to load
		await page.waitForSelector('.wp-list-table', { timeout: 10000 });


		// Look for our plugin row using the data-slug attribute
		const pluginRow = page.locator('tr[data-slug="custom-ptt"]');

		if (await pluginRow.first().isVisible()) {
			// Check if plugin needs activation using the specific ID
			const activateLink = page.locator('#activate-custom-ptt');

			if (await activateLink.isVisible()) {
				await activateLink.first().click();

				// Wait for page navigation/reload after activation
				await page.waitForLoadState('networkidle', { timeout: 15000 });

				// Verify activation by checking if row class changed from 'inactive' to 'active'
				await page.waitForSelector('tr[data-slug="custom-ptt"]:not(.inactive)', { timeout: 10000 });
				console.log('✅ Plugin activated successfully');
			}
		}
	} catch (error) {
		console.log('⚠️ Error during plugin activation:', error.message);
	}

	// Navigate to dashboard to ensure we're in a good state
	await page.goto('http://localhost:8889/wp-admin/');
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
