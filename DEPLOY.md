# Putting kuvadoo.fi online (Hostinger)

Simple steps. Hostinger sometimes renames menu items; if a name is different, look for the closest one.

## What you need

- The site in **3 zip files**: `kuvadoo.fi-part1-of-3.zip`, `-part2-of-3.zip`, `-part3-of-3.zip`
  (pages, photos, texts, prices; split only because of a file-size limit). All three go to the same folder.
- Your Hostinger login.

## Step 1 – Test address first (the old site stays online)

1. hPanel → **Websites** → **Add website** → choose **PHP/HTML** (or "Empty website") → use the domain
   **uusi.kuvadoo.fi** (a subdomain of kuvadoo.fi). This is the same way you made apps.kuvadoo.fi.
2. hPanel → that website → **Security → SSL** → install the free SSL certificate.
3. hPanel → that website → **Advanced → PHP Configuration** → choose **PHP 8.2 or newer**.
   In "PHP options" check that `upload_max_filesize` is at least **40M** and `post_max_size` at least **400M**
   (the site's `.user.ini` asks for this; if hPanel shows smaller numbers, change them there).
4. **File Manager** → `public_html` → delete the default files (e.g. `default.php`) → **Upload** all 3 zip
   files → right-click each one → **Extract** into `public_html` (the same folder; "overwrite" is fine) →
   delete the 3 zips.
   Check that `index.php`, `.htaccess`, `app/`, `data/`, `media/` are directly in `public_html`.
   (`.htaccess` and `.user.ini` are hidden files – turn on "Show hidden files" to see them.)
5. Open **https://uusi.kuvadoo.fi/** – the new site should show.

## Step 2 – Set your admin password

1. Open **https://uusi.kuvadoo.fi/hallinta/**. It asks for a setup code.
2. File Manager → `public_html/data/setup-code.txt` → open it and copy the code.
3. Paste the code, choose your password (at least 10 characters). The code file is then deleted.
4. Now you can change photos, videos, prices and texts. Your changes are saved on the server.

Forgot the password? File Manager → delete `public_html/data/auth.json` → open `/hallinta/` again → a new
setup code is created in `data/setup-code.txt`.

## Step 3 – Check

- Open every menu item in Finnish and English (EN/FI button), on your phone too.
- Press a video's play button. Open a gallery photo and swipe.
- WhatsApp and email buttons open the right number/address.
- https://uusi.kuvadoo.fi/data/content.json must show "not found" (private files are protected).

## Step 4 – Switch kuvadoo.fi to the new site

Do this when you are happy with the test site. The site can be down for a few minutes.
Your email (kuvadoo@gmail.com) is not affected.

1. In the admin of the test site: **Settings → Download backup** (keeps all your changes).
2. Website Builder (the old site) → **Settings / Domain** → disconnect **kuvadoo.fi** (move the old site to a
   temporary free address – it is not deleted, so you can go back).
3. hPanel → the new website (uusi.kuvadoo.fi) → **Dashboard → Change domain** → **kuvadoo.fi**.
   If you don't find "Change domain": add a new PHP/HTML website for **kuvadoo.fi** and repeat Step 1 there,
   then copy `data/` and `media/` from the test site (or from the backup zip) into its `public_html`.
4. Install SSL for **kuvadoo.fi** and **www.kuvadoo.fi**.
5. Check: https://kuvadoo.fi/ works, https://www.kuvadoo.fi/ goes to https://kuvadoo.fi/, and old addresses
   like https://kuvadoo.fi/videos and https://kuvadoo.fi/hinnasto go to the new pages.
6. Google: in Google Search Console add kuvadoo.fi and submit `https://kuvadoo.fi/sitemap.xml` (optional).

## Later updates from Claude

- **Code/design update:** you get `kuvadoo.fi-update.zip`. It has **no** `data/` and `media/` folders, so your
  photos, prices and texts stay. Upload to `public_html` → Extract → overwrite → delete the zip.
- Never upload the 3 full-site zips again after you have made changes in the admin – it would replace
  your changes with the old content. (If it happens: restore your backup zip's `data/` and `media/`.)
