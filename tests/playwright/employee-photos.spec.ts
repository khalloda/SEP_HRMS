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

async function ensureAtLeastOneEmployeeWithPhoto(page) {
  await page.goto('/employees');
  const photoElement = page.locator('table img[alt*="EMP"]').first();
  await expect(photoElement).toBeVisible();
}

test.describe('Employee Photos', () => {
  test('shows employee photo on profile and list', async ({ page }) => {
    await loginAsHrAdmin(page);

    await page.goto('/employees');
    const firstRow = page.locator('table tbody tr').first();
    await expect(firstRow).toBeVisible();

    const thumbnail = firstRow.locator('img').first();
    await expect(thumbnail).toBeVisible();
    const thumbnailSrc = await thumbnail.getAttribute('src');
    expect(thumbnailSrc).toBeTruthy();

    await firstRow.locator('a:has-text("View")').first().click();
    await expect(page).toHaveURL(/\/employees\/(\d+)/);

    const mainPhoto = page.locator('img[alt*="EMP"]')
      .filter({ has: page.locator('.border-brand-gold') })
      .first();
    await expect(mainPhoto).toBeVisible();
    const mainPhotoSrc = await mainPhoto.getAttribute('src');
    expect(mainPhotoSrc).toBeTruthy();

    // Signed URL should include signature parameters.
    expect(mainPhotoSrc).toContain('signature=');
    expect(mainPhotoSrc).toContain('hash=');
  });
});
