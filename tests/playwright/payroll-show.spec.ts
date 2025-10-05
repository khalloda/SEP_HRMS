import { test, expect } from '@playwright/test';

async function loginAsHrAdmin(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'hr@sarieldin.com');
  await page.fill('input[name="password"]', 'password123');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('button[type="submit"]'),
  ]);
  await expect(page.locator('text=Dashboard').first()).toBeVisible();
}

test.describe('Payroll Run Show Lifecycles', () => {
  test('shows lifecycle buttons and cancellation modal', async ({ page }) => {
    await loginAsHrAdmin(page);

    await page.goto('/payroll');
    const firstView = page.locator('a:has-text("View")').first();
    await expect(firstView).toBeVisible();
    await firstView.click();

    await expect(page).toHaveURL(/\/payroll\/(\d+)/);
    await expect(page.locator("form[action*='/calculate'] button:has-text('Calculate')")).toBeVisible({ timeout: 5000 });
    await expect(page.locator("button[data-bs-target^='#cancelPayrollModal']")).toBeVisible();

    await page.locator("button[data-bs-target^='#cancelPayrollModal']").click();
    const modal = page.locator('.modal-dialog');
    await expect(modal).toBeVisible();
    await expect(modal.locator('textarea[name="cancellation_reason"]')).toBeVisible();

    await modal.locator('button:has-text("Close")').click();
    await expect(modal).toBeHidden();
  });
});
