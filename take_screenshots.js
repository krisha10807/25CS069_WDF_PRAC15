/**
 * Practical 15 – Final Portfolio Submission
 * Auto-Screenshot Script (Puppeteer)
 * Student: Krisha Patel | ID: 25CS069
 */

const puppeteer = require('d:\\WDF____\\StudentHub\\Practical_3\\node_modules\\puppeteer-core');
const { execSync, spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

const PORT = 8100;
const BASE = `http://localhost:${PORT}`;
const SHOTS_DIR = path.join(__dirname, 'screenshots');
const PHP_EXE = 'C:\\xampp\\php\\php.exe';
const BROWSER_PATH = fs.existsSync('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
    ? 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe'
    : 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';

if (!fs.existsSync(SHOTS_DIR)) fs.mkdirSync(SHOTS_DIR, { recursive: true });

async function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

async function run() {
    console.log('🚀 Starting PHP server on port', PORT);
    const php = spawn(PHP_EXE, ['-S', `localhost:${PORT}`, '-t', __dirname], {
        stdio: ['ignore', 'pipe', 'pipe'],
        windowsHide: true
    });

    php.stdout.on('data', d => {});
    php.stderr.on('data', d => {});

    await sleep(2000);

    console.log('🌐 Launching browser...');
    const userDataDir = path.join(__dirname, '.temp_chrome_p15');
    if (fs.existsSync(userDataDir)) {
        try { fs.rmSync(userDataDir, { recursive: true, force: true }); } catch(e) {}
    }

    const browser = await puppeteer.launch({
        executablePath: BROWSER_PATH,
        headless: 'new',
        userDataDir: userDataDir,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-gpu',
            '--window-size=1400,900'
        ],
        defaultViewport: { width: 1400, height: 900 }
    });

    const page = await browser.newPage();

    try {
        // Screenshot 1: Main Portfolio Dashboard (hero + stats)
        console.log('📸 1/5 – Portfolio Dashboard');
        await page.goto(`${BASE}/index.php`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1500);
        await page.screenshot({ path: path.join(SHOTS_DIR, '01_portfolio_dashboard_hero.png'), fullPage: false });

        // Screenshot 2: Full Dashboard (scroll to practicals section)
        console.log('📸 2/5 – GitHub Repo & Practicals Overview');
        await page.evaluate(() => {
            const el = document.querySelector('.github-banner');
            if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
        });
        await sleep(800);
        await page.screenshot({ path: path.join(SHOTS_DIR, '02_github_repo_and_practicals.png'), fullPage: false });

        // Screenshot 3: Data Tables (students, events, courses, audit)
        console.log('📸 3/5 – Data Tables & Security Summary');
        await page.evaluate(() => {
            const tables = document.querySelectorAll('.section-title');
            for (const t of tables) {
                if (t.textContent.includes('Recent Students')) {
                    t.scrollIntoView({ behavior: 'instant', block: 'start' });
                    break;
                }
            }
        });
        await sleep(800);
        await page.screenshot({ path: path.join(SHOTS_DIR, '03_data_tables_students_events.png'), fullPage: false });

        // Screenshot 4: Security checklist section on main page
        console.log('📸 4/5 – Security Checklist on Main Page');
        await page.evaluate(() => {
            const titles = document.querySelectorAll('.section-title');
            for (const t of titles) {
                if (t.textContent.includes('Security')) {
                    t.scrollIntoView({ behavior: 'instant', block: 'start' });
                    break;
                }
            }
        });
        await sleep(800);
        await page.screenshot({ path: path.join(SHOTS_DIR, '04_security_checklist_main.png'), fullPage: false });

        // Screenshot 5: Test & Security Report page
        console.log('📸 5/5 – Test & Security Report Page');
        await page.goto(`${BASE}/test_security_report.php`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1500);
        await page.screenshot({ path: path.join(SHOTS_DIR, '05_test_security_report.png'), fullPage: false });

        console.log('✅ All 5 screenshots captured!');

    } catch (err) {
        console.error('❌ Error:', err.message);
    } finally {
        await browser.close();
        php.kill();
        // Clean up temp chrome dir
        try { fs.rmSync(userDataDir, { recursive: true, force: true }); } catch(e) {}
        console.log('🧹 Cleanup done.');
    }
}

run().catch(console.error);
