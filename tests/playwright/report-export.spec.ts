import { test, expect } from '@playwright/test';
import { existsSync } from 'fs';

async function loginAsHrAdmin(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'hr@sarieldin.com');
    await page.fill('input[name="password"]', 'password123');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/');
    await expect(page.locator('text=Dashboard').first()).toBeVisible();
}

test.describe('Report Exports (new pipeline)', () => {
    test('employee directory export queues and completes', async ({ page }) => {
        await loginAsHrAdmin(page);

        await page.goto('/reports/employee-list');
        await expect(page.locator('button', { hasText: 'Export to Excel' })).toBeVisible();

        await page.click('button:has-text("Export to Excel")');
        await page.waitForURL(/\/reports\/exports\//, { waitUntil: 'load' });

        await expect(page).toHaveURL(/\/reports\/exports\//);
        await expect(page.locator('#report-status')).toBeVisible();

        await page.waitForFunction(() => {
            const statusEl = document.getElementById('report-status');
            return statusEl && statusEl.dataset.status === 'completed';
        }, null, { timeout: 30000 });
        await expect(page.locator('#report-status')).toHaveAttribute('data-status', 'completed');

        const downloadButton = page.locator('#report-download');
        await expect(downloadButton).toBeVisible();

        const [download] = await Promise.all([
            page.waitForEvent('download'),
            downloadButton.click(),
        ]);

        const downloadPath = `tests/playwright/artifacts/${Date.now()}-${download.suggestedFilename()}`;
        await download.saveAs(downloadPath);
        expect(existsSync(downloadPath)).toBe(true);
    });

    test('contract status export queues and completes', async ({ page }) => {
        await loginAsHrAdmin(page);

        await page.goto('/reports/contract-status');
        await expect(page.locator('button', { hasText: 'Export to Excel' })).toBeVisible();

        await page.click('button:has-text("Export to Excel")');
        await page.waitForURL(/\/reports\/exports\//, { waitUntil: 'load' });

        await expect(page).toHaveURL(/\/reports\/exports\//);
        await expect(page.locator('#report-status')).toBeVisible();

        await page.waitForFunction(() => {
            const statusEl = document.getElementById('report-status');
            return statusEl && statusEl.dataset.status === 'completed';
        }, null, { timeout: 30000 });
        await expect(page.locator('#report-status')).toHaveAttribute('data-status', 'completed');

        const downloadButton = page.locator('#report-download');
        await expect(downloadButton).toBeVisible();

        const [download] = await Promise.all([
            page.waitForEvent('download'),
            downloadButton.click(),
        ]);

        const downloadPath = `tests/playwright/artifacts/${Date.now()}-${download.suggestedFilename()}`;
        await download.saveAs(downloadPath);
        expect(existsSync(downloadPath)).toBe(true);
    });

    test('payroll summary export queues and completes', async ({ page }) => {
        await loginAsHrAdmin(page);

        await page.goto('/reports/payroll-summary');
        await expect(page.locator('button', { hasText: 'Export to Excel' })).toBeVisible();

        await page.click('button:has-text("Export to Excel")');
        await page.waitForURL(/\/reports\/exports\//, { waitUntil: 'load' });

        await expect(page).toHaveURL(/\/reports\/exports\//);
        await expect(page.locator('#report-status')).toBeVisible();

        await page.waitForFunction(() => {
            const statusEl = document.getElementById('report-status');
            return statusEl && statusEl.dataset.status === 'completed';
        }, null, { timeout: 30000 });
        await expect(page.locator('#report-status')).toHaveAttribute('data-status', 'completed');

        const downloadButton = page.locator('#report-download');
        await expect(downloadButton).toBeVisible();

        const [download] = await Promise.all([
            page.waitForEvent('download'),
            downloadButton.click(),
        ]);

        const downloadPath = `tests/playwright/artifacts/${Date.now()}-${download.suggestedFilename()}`;
        await download.saveAs(downloadPath);
        expect(existsSync(downloadPath)).toBe(true);
    });
});
