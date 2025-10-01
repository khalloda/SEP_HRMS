import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/playwright',
    timeout: 60_000,
    expect: {
        timeout: 10_000,
    },
    use: {
        baseURL: process.env.PLAYWRIGHT_BASE_URL ?? 'http://localhost:8000',
        headless: true,
        viewport: { width: 1440, height: 900 },
        ignoreHTTPSErrors: true,
        video: 'retain-on-failure',
        screenshot: 'only-on-failure',
        acceptDownloads: true,
    },
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],
});
