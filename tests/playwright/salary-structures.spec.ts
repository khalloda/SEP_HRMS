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

function todayDate() {
    const now = new Date();
    const yyyy = now.getFullYear();
    const mm = String(now.getMonth() + 1).padStart(2, '0');
    const dd = String(now.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

async function gatherComponentOptions(page) {
    return page.evaluate(() => {
        const select = document.querySelector('.list-group-item select');
        if (!select) {
            return [];
        }

        const seen = new Set();
        const values = [];
        select.querySelectorAll('option').forEach(option => {
            if (option.value && !seen.has(option.value)) {
                seen.add(option.value);
                values.push(option.value);
            }
        });

        return values;
    });
}

async function selectComponentForRow(row, value) {
    const select = row.locator('select');
    await select.selectOption(value);
}

async function fillAmountForRow(row, amount) {
    const amountInput = row.locator('input[type="number"]');
    await amountInput.fill(amount);
}

const TARGET_EMPLOYEE_ID = process.env.PLAYWRIGHT_EMPLOYEE_ID ?? '51';

test.describe('Salary Structure Creation', () => {
    test('HR admin can add multiple components to a new salary structure', async ({ page }) => {
        await loginAsHrAdmin(page);

        await page.goto(`/employees/${TARGET_EMPLOYEE_ID}/salary-structures/create`);

        await expect(page.locator('h1')).toContainText('Create Salary Structure');

        await page.fill('#effective_from', todayDate());
        await page.fill('#notes', 'Playwright automated structure');

        const addButton = page.locator('button:has-text("Add Component")');
        await addButton.click();
        await addButton.click();

        const componentRows = page.locator('.list-group-item');
        await expect(componentRows.first()).toBeVisible({ timeout: 15000 });
        await expect(componentRows).toHaveCount(3);

        const optionValues = await gatherComponentOptions(page);
        expect(optionValues.length).toBeGreaterThan(0);

        for (let index = 0; index < 3; index += 1) {
            const row = componentRows.nth(index);
            const value = optionValues[index] ?? optionValues[0];
            await selectComponentForRow(row, value);
            await fillAmountForRow(row, `${1000 + index * 100}`);
        }

        await page.click('button:has-text("Create Structure")');

        await expect(page).toHaveURL(new RegExp(`/employees/${TARGET_EMPLOYEE_ID}/salary-structures$`));
        await expect(page.locator('.alert-success').first()).toContainText('created successfully');
    });
});
