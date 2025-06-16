const { test, expect } = require('@playwright/test');

test.describe('Taxonomy Functionality', () => {
	// Use saved authentication state for all tests
	test.use({ storageState: 'tests/e2e/fixtures/admin-auth-state.json' });

	test('Test 1: Taxonomies page loads and shows empty state', async ({ page }) => {
		// Navigate to the main taxonomies page
		await page.goto('http://localhost:8888/wp-admin/admin.php?page=custom-post-types-taxonomies');

		// Should see the main heading (avoid Query Monitor h1)
		await expect(page.locator('h1').filter({ hasText: 'Custom Taxonomies' }).or(page.locator('h1').filter({ hasText: 'custom ptt' }))).toBeVisible();

		// Should see "Add New" button
		await expect(page.locator('a.page-title-action')).toContainText('Add New');

		// Should see the list table (even if empty) - use first() to avoid strict mode
		await expect(page.locator('.wp-list-table, .no-items').first()).toBeVisible();
	});

	test('Test 2: Navigate to taxonomy creation form', async ({ page }) => {
		// Start from taxonomies list page
		await page.goto('/wp-admin/admin.php?page=custom-post-types-taxonomies');

		// Click "Add New" button
		await page.click('a.page-title-action');

		// Should navigate to the form page
		await expect(page).toHaveURL(/.*add-custom-post-types-taxonomies-taxonomies.*/);

		// Should see "Add New Taxonomy" heading (avoid Query Monitor h1)
		await expect(page.locator('h1').filter({ hasText: 'Add New Taxonomy' })).toBeVisible();

		// Should see the required form fields
		await expect(page.locator('#taxonomy-slug')).toBeVisible();
		await expect(page.locator('#singular-label')).toBeVisible();
		await expect(page.locator('#plural-label')).toBeVisible();

		// Should see post type checkboxes (use first() to avoid strict mode)
		await expect(page.locator('input[name="post-type[]"]').first()).toBeVisible();

		// Should see submit button
		await expect(page.locator('input[type="submit"]')).toBeVisible();
	});

	test('Test 3: Create a new taxonomy successfully', async ({ page }) => {
		// Navigate directly to the form
		await page.goto('/wp-admin/admin.php?page=add-custom-post-types-taxonomies-taxonomies');

		// Fill out the form with test data
		await page.fill('#taxonomy-slug', 'e2e_test_category');
		await page.fill('#singular-label', 'E2E Test Category');
		await page.fill('#plural-label', 'E2E Test Categories');

		// Select at least one post type (check the "post" checkbox)
		await page.check('input[name="post-type[]"][value="post"]');

		// Submit the form
		await page.click('input[type="submit"]');

		// Wait for form submission to complete
		await page.waitForLoadState('networkidle');

		// Should redirect back to the list page or show success
		// Check if we're back on the main page or see a success message
		const isOnListPage = page.url().includes('custom-post-types-taxonomies') &&
			!page.url().includes('add-custom-post-types-taxonomies-taxonomies');
		const hasSuccessNotice = await page.locator('.notice-success, .updated').isVisible().catch(() => false);

		// At least one of these should be true (taxonomy creation was successful)
		expect(isOnListPage || hasSuccessNotice).toBe(true);
	});

	test('Test 4: Form validation - require taxonomy slug', async ({ page }) => {
		// Navigate to the form
		await page.goto('http://localhost:8888/wp-admin/admin.php?page=add-custom-post-types-taxonomies-taxonomies');

		// Fill only some fields, leave taxonomy-slug empty
		await page.fill('#singular-label', 'Test Category');
		await page.fill('#plural-label', 'Test Categories');
		await page.check('input[name="post-type[]"][value="post"]');

		// Try to submit without taxonomy slug
		await page.click('input[type="submit"]');

		// Should stay on form page due to HTML5 validation or server validation
		await expect(page).toHaveURL(/.*add-custom-post-types-taxonomies-taxonomies.*/);

		// The required field should prevent submission (HTML5 validation)
		const slugField = page.locator('#taxonomy-slug');
		await expect(slugField).toHaveAttribute('required');
	});

	test('Test 5: Form validation - require singular label', async ({ page }) => {
		// Navigate to the form
		await page.goto('http://localhost:8888/wp-admin/admin.php?page=add-custom-post-types-taxonomies-taxonomies');

		// Fill taxonomy slug but leave singular label empty
		await page.fill('#taxonomy-slug', 'test_category_validation');
		await page.fill('#plural-label', 'Test Categories');
		await page.check('input[name="post-type[]"][value="post"]');

		// Try to submit without singular label
		await page.click('input[type="submit"]');

		// Should stay on form page due to validation
		await expect(page).toHaveURL(/.*add-custom-post-types-taxonomies-taxonomies.*/);

		// The required field should prevent submission
		const singularField = page.locator('#singular-label');
		await expect(singularField).toHaveAttribute('required');
	});
});
