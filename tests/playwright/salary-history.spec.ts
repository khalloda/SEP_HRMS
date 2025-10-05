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

test.describe('Salary History', () => {
  test('renders history page and exports', async ({ page }) => {
    await loginAsHrAdmin(page);

    // Navigate to an employee show page then click Salary History action if present
    await page.goto('/employees');
    // If list is empty, navigate directly to employee 1
    const firstView = page.locator('a:has-text("View")').first();
    if (await firstView.count()) {
      await firstView.click();
    } else {
      await page.goto('/employees/1');
    }

    // Click Salary History button when available
    const historyBtn = page.locator('a:has-text("Salary History")').first();
    if (await historyBtn.count()) {
      await historyBtn.click();
    } else {
      // fallback: direct url to employee 1
      await page.goto('/employees/1/salary-history');
    }

    // Navigate directly to salary-history to avoid UI differences
    await page.goto('/employees/1/salary-history');
    await expect(page).toHaveURL(/\/employees\/\d+\/salary-history/);
    // Page should show filter form controls or export links
    const filterFrom = page.locator('input[name="from"]');
    await expect(filterFrom).toBeVisible({ timeout: 15000 });

    // Try Excel export
    const excelLink = page.locator('a:has-text("Excel")');
    if (await excelLink.count()) {
      const [ download ] = await Promise.all([
        page.waitForEvent('download'),
        excelLink.click(),
      ]);
      const name = await download.suggestedFilename();
      expect(name).toMatch(/salary-history.*\.xlsx$/i);
    }

    // Try PDF export
    const pdfLink = page.locator('a:has-text("PDF")');
    if (await pdfLink.count()) {
      const [ download ] = await Promise.all([
        page.waitForEvent('download'),
        pdfLink.click(),
      ]);
      const name = await download.suggestedFilename();
      expect(name).toMatch(/\.pdf$/i);
    }
  });
});


