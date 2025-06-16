const { test, expect } = require('@playwright/test');

test.describe('Basic Plugin Functionality', () => {
	// Use saved authentication state for all tests
	test.use({ storageState: 'tests/e2e/fixtures/admin-auth-state.json' });

	test('Test 1: Can login and see WordPress admin', async ({ page }) => {
		// Navigate to WordPress admin dashboard
		await page.goto('http://localhost:8888/wp-admin/');

		// Should see the admin bar indicating we're logged in
		await expect(page.locator('#wpadminbar')).toBeVisible();

		// Should see the main admin menu
		await expect(page.locator('#adminmenu')).toBeVisible();

		// Should see "Dashboard" in the page - use first() to avoid strict mode violations
		await expect(page.locator('#adminmenu a[href="index.php"]').first()).toBeVisible();
	});

	test('Test 2: Plugin is active and menu exists', async ({ page }) => {
		// Navigate to WordPress admin dashboard
		await page.goto('http://localhost:8888/wp-admin/');

		// Look for our plugin menu item "Custom PTT" using specific selector
		const pluginMenu = page.locator('a.toplevel_page_custom-post-types-taxonomies');
		await expect(pluginMenu).toBeVisible();

		// Verify the menu text
		await expect(pluginMenu.locator('.wp-menu-name')).toContainText('Custom PTT');

		// Click on the plugin menu to ensure it works
		await pluginMenu.click();

		// Should navigate to our plugin page (taxonomies list)
		await expect(page).toHaveURL(/.*custom-post-types-taxonomies.*/);

		// Should see the taxonomies page heading (avoid Query Monitor h1)
		await expect(page.locator('h1').filter({ hasText: 'Custom Taxonomies' }).or(page.locator('h1').filter({ hasText: 'custom ptt' }))).toBeVisible();
	});

});
