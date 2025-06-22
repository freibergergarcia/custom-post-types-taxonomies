const fs = require('fs');
const path = require('path');

/**
 * Global teardown for E2E tests.
 *
 * Cleans up any temporary files and state created during testing.
 */
async function globalTeardown() {
	// Clean up authentication state file
	const authStatePath = path.join(__dirname, 'fixtures', 'admin-auth-state.json');
	if (fs.existsSync(authStatePath)) {
		fs.unlinkSync(authStatePath);
		console.log('🧹 Cleaned up authentication state');
	}

	console.log('✅ Global teardown completed');
}

module.exports = globalTeardown;
