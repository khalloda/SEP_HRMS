import { test, expect } from '@playwright/test';

const BASE_EMAIL = process.env.PLAYWRIGHT_USER ?? 'hr@sarieldin.com';
const BASE_PASSWORD = process.env.PLAYWRIGHT_PASSWORD ?? 'password123';
const TARGET_EMPLOYEE_ID = process.env.PLAYWRIGHT_EMPLOYEE_ID ?? '51';

async function loginAsHrAdmin(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', BASE_EMAIL);
    await page.fill('input[name="password"]', BASE_PASSWORD);
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

test.describe('Salary Structures', () => {
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

    test('Edit form preserves existing component selections', async ({ page }) => {
        await loginAsHrAdmin(page);

        await page.goto(`/employees/${TARGET_EMPLOYEE_ID}/salary-structures`);

        await page.waitForURL(new RegExp(`/employees/${TARGET_EMPLOYEE_ID}/salary-structures$`));

        const editLink = page.locator('a.btn.btn-outline-secondary').first();
        await expect(editLink).toBeVisible();
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            editLink.click(),
        ]);

        const rows = page.locator('.list-group-item');
        await expect(rows).toHaveCountGreaterThan(0);

        const selectedValues = await rows.evaluate((elements) =>
            elements.map((element) => {
                const select = element.querySelector('select');
                return select ? select.value : null;
            }),
        );

        const uniqueValues = new Set(selectedValues.filter(Boolean));
        if (uniqueValues.size <= 1) {
            throw new Error(`Expected distinct component values but saw: ${JSON.stringify(selectedValues)}`);
        }

        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('button:has-text("Save Changes")'),
        ]);

        await expect(page).toHaveURL(new RegExp(`/employees/${TARGET_EMPLOYEE_ID}/salary-structures/`));

        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('a:has-text("Edit")'),
        ]);

        const rowsAfter = page.locator('.list-group-item');
        await expect(rowsAfter).toHaveCountGreaterThan(0);

        const valuesAfter = await rowsAfter.evaluate((elements) =>
            elements.map((element) => {
                const select = element.querySelector('select');
                return select ? select.value : null;
            }),
        );

        expect(new Set(valuesAfter.filter(Boolean))).toEqual(uniqueValues);
    });
});
