// End-to-end test of the admin panel on a throwaway copy of the site.
// Usage: copy the site to a throwaway folder, run `php -S 127.0.0.1:8766 router.php` there, then
// node tools/admin-test.mjs <copy folder> <any photo.jpg>   (needs Playwright; see the site-check skill)
import { chromium } from 'playwright';
import { readFileSync } from 'node:fs';
const B = 'http://127.0.0.1:8766';
const site = process.argv[2];
const photo = process.argv[3];
let fails = 0;
const ok = (c, m) => { console.log((c ? 'ok   ' : 'FAIL ') + m); if (!c) fails++; };
const b = await chromium.launch();
const ctx = await b.newContext({ viewport: { width: 390, height: 844 } });
const p = await ctx.newPage();
const errs = []; p.on('console', m => m.type() === 'error' && errs.push(m.text()));

// 1. Setup with wrong code, then right code
await p.goto(B + '/hallinta/');
ok(await p.locator('h1', { hasText: 'Set your admin password' }).count() === 1, 'setup page shown');
await p.fill('#code', 'WRONGCODE'); await p.fill('#pw', 'testpassword1'); await p.fill('#pw2', 'testpassword1');
await p.click('button[type=submit]');
ok(await p.getByText('The setup code is wrong').count() === 1, 'wrong setup code rejected');
const code = readFileSync(site + '/data/setup-code.txt', 'utf8').trim();
await p.fill('#code', code); await p.fill('#pw', 'short'); await p.fill('#pw2', 'short');
await p.locator('#pw').evaluate(e => e.removeAttribute('minlength')); await p.locator('#pw2').evaluate(e => e.removeAttribute('minlength'));
await p.click('button[type=submit]');
ok(await p.locator('.a-flash-error', { hasText: 'at least 10' }).count() === 1, 'short password rejected');
await p.fill('#code', code); await p.fill('#pw', 'testpassword1'); await p.fill('#pw2', 'testpassword1');
await p.click('button[type=submit]');
ok(await p.getByText('Hello, Prasath').count() === 1, 'setup done, dashboard');

// 2. Create album + upload
await p.goto(B + '/hallinta/albums/');
await p.fill('#title_fi', 'Testihäät 2027'); await p.fill('#title_en', 'Test wedding 2027');
await p.selectOption('#cat', 'weddings');
await p.click('form:has(input[value=create]) button[type=submit]');
ok(await p.getByText('Album created').count() === 1, 'album created');
await p.setInputFiles('#up', [photo, photo]);
await p.click('form.a-upload button[type=submit]');
ok(await p.getByText('2 photo(s) added').count() === 1, 'two photos uploaded');
// 3. Edit alt, set hero, save
const alt = p.locator('input[name$="[fi]"][name^="alt"]').first();
await alt.fill('Testikuvan kuvaus');
await p.locator('input[name=hero]').first().check();
await p.click('button:has-text("Save album")');
ok(await p.getByText('Saved.').count() === 1, 'album saved');
const albumUrl = await p.locator('a:has-text("View on site")').getAttribute('href');
const pub = await ctx.newPage();
await pub.goto(B + albumUrl);
ok(await pub.locator('img[alt="Testikuvan kuvaus"]').count() === 1, 'alt text visible on public album ' + albumUrl);
await pub.goto(B + '/valokuvaus/haat/');
ok(await pub.getByText('Testihäät 2027').count() >= 1, 'weddings category shows new album');
await pub.goto(B + '/');
const heroSrc = await pub.locator('.hero-img').getAttribute('src');
ok(heroSrc.includes('testihaat-2027'), 'home hero changed: ' + heroSrc);
// 4. Delete one photo
await p.locator('input[name^=delete]').first().check();
await p.click('button:has-text("Save album")');
ok(await p.getByText('1 photo(s) deleted').count() === 1, 'photo deleted');

// 5. Video add (bad link, good link)
await p.goto(B + '/hallinta/videos/');
await p.fill('#yt', 'not a link'); await p.fill('#title_fi', 'x');
await p.click('form:has(input[value=add]) button[type=submit]');
ok(await p.getByText('does not look like a YouTube link').count() === 1, 'bad YouTube link rejected');
await p.fill('#yt', 'https://youtu.be/st2p772Tlcs?si=abc'); await p.fill('#title_fi', 'Testivideo'); await p.fill('#title_en', 'Test video');
await p.click('form:has(input[value=add]) button[type=submit]');
ok(await p.getByText('Video added').count() === 1, 'video added');
await pub.goto(B + '/hameen-films/');
ok(await pub.getByText('Testivideo').count() >= 1, 'video on films page');

// 6. Prices: change first price, add new package
await p.goto(B + '/hallinta/prices/');
const firstPrice = p.locator('input[name$="[price]"]').first();
await firstPrice.fill('155');
const newName = p.locator('fieldset.a-new input[name$="[name][fi]"]').first();
await newName.fill('Testipaketti'); 
await p.locator('fieldset.a-new input[name$="[price]"]').first().fill('1 234');
await p.click('button:has-text("Save prices")');
ok(await p.getByText('Prices saved').count() === 1, 'prices saved');
await pub.goto(B + '/hinnasto/');
const txt = await pub.locator('main').innerText();
ok(txt.includes('155') && txt.includes('Testipaketti') && /1\s?234/.test(txt.replace(/ /g, ' ')), 'new prices on public page');

// 7. Texts: change hero title, bad link
await p.goto(B + '/hallinta/texts/');
await p.locator('details:has-text("Home page – top")').locator('summary').click();
await p.fill('#t_hero_title__fi', 'Uusi otsikko testi');
await p.fill('#s_instagram', 'javascript:alert(1)');
await p.click('button:has-text("Save texts")');
ok(await p.getByText('must start with https://').count() === 1, 'bad social link rejected');
await p.fill('#s_instagram', 'https://www.instagram.com/kuvadoo/');
await p.locator('details:has-text("Home page – top")').locator('summary').click();
await p.fill('#t_hero_title__fi', 'Uusi otsikko testi');
await p.click('button:has-text("Save texts")');
ok(await p.getByText('Texts saved').count() === 1, 'texts saved');
await pub.goto(B + '/');
ok(await pub.locator('h1').innerText() === 'Uusi otsikko testi', 'hero title changed on site');

// 8. XSS check: script in a title is escaped
await p.goto(B + '/hallinta/albums/');
await p.fill('#title_fi', '<script>alert(1)</script>'); await p.selectOption('#cat', 'events');
await p.click('form:has(input[value=create]) button[type=submit]');
const html = await p.content();
ok(!html.includes('<script>alert(1)</script>'), 'album title escaped in admin');

// 9. CSRF: post without token
const r = await ctx.request.post(B + '/hallinta/prices/', { form: { 'p[photo][mini][name][fi]': 'hack' } });
ok(r.status() === 400, 'POST without CSRF token rejected (' + r.status() + ')');

// 10. Backup download
await p.goto(B + '/hallinta/settings/');
const [dl] = await Promise.all([p.waitForEvent('download'), p.click('button:has-text("Download backup")')]);
ok(dl.suggestedFilename().startsWith('kuvadoo-backup-'), 'backup downloads: ' + dl.suggestedFilename());

// 11. Password change, logout, wrong password throttle
await p.fill('#cur', 'testpassword1'); await p.fill('#pw', 'testpassword2'); await p.fill('#pw2', 'testpassword2');
await p.click('button:has-text("Change password")');
ok(await p.getByText('Password changed').count() === 1, 'password changed');
await p.click('button:has-text("Log out")');
ok(await p.locator('h1', { hasText: 'Log in' }).count() === 1, 'logged out');
await p.goto(B + '/hallinta/prices/');
ok(await p.locator('h1', { hasText: 'Log in' }).count() === 1, 'admin pages need login');
for (let i = 0; i < 5; i++) { await p.fill('#pw', 'wrong' + i); await p.click('button[type=submit]'); }
await p.fill('#pw', 'testpassword2'); await p.click('button[type=submit]');
ok(await p.getByText('Too many wrong passwords').count() === 1, 'login blocked after 5 wrong passwords');

// 12. Admin cookie path and flags
const cookies = await ctx.cookies(B + '/hallinta/');
const ck = cookies.find(c => c.name === 'kuvadoo_admin');
ok(ck && ck.path === '/hallinta/' && ck.httpOnly && ck.sameSite === 'Strict', 'admin cookie scoped + httpOnly + Strict');
await pub.goto(B + '/');
ok((await ctx.cookies(B + '/')).filter(c => c.path === '/').length === 0, 'public pages set no cookies');
ok(errs.length === 0, 'no console errors ' + errs.join(' | '));
await b.close();
console.log(fails ? `\n${fails} FAILED` : '\nAll admin tests passed');
process.exit(fails ? 1 : 0);
