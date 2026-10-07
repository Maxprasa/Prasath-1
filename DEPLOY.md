# Deploying apps.kuvadoo.fi (Hostinger)

The site is plain static files. The repository root is the website root.

## Before the first upload (once)

1. hPanel → **Websites** → the kuvadoo.fi site → **Subdomains** → create `apps`.
   Note the folder Hostinger creates for it (usually `public_html/apps`).
2. hPanel → **Security → SSL** → install the free SSL certificate for `apps.kuvadoo.fi`.

## Option A: File Manager (zip upload)

1. Make the zip (or use the one supplied): from the repository root run
   `zip -r apps.kuvadoo.fi.zip . -x '.git/*' 'DEPLOY.md' '.gitignore' 'apps.kuvadoo.fi.zip'`
2. hPanel → **Files → File Manager** → open the subdomain's folder (e.g. `public_html/apps`).
3. Delete any default files there (such as `default.php`).
4. **Upload** `apps.kuvadoo.fi.zip`, right-click it → **Extract** into the same folder, then delete the zip.
5. Check that `index.html`, `.htaccess`, `assets/` and `finnsana/` sit directly in the subdomain's folder
   (not in a subfolder). `.htaccess` is a hidden file: turn on "show hidden files" if you don't see it.

## Option B: Git deployment

1. hPanel → **Advanced → Git** → repository `https://github.com/Maxprasa/Prasath-1`, branch with the site
   (e.g. `main`), install path = the subdomain's folder (e.g. `public_html/apps`). The folder must be empty.
   For a private repository, add Hostinger's SSH key as a deploy key on GitHub first.
2. Click **Deploy**. Redeploy after each change (or set up the auto-deployment webhook Hostinger shows).
   `.htaccess` hides `.git`, `DEPLOY.md` and `.gitignore` from visitors.

## After uploading

- Open https://apps.kuvadoo.fi/, /finnsana/, /finnsana/delete-account/ and a missing page (shows the 404 page).
- When SSL works, uncomment the three "Force HTTPS" lines at the end of `.htaccess`.

## Later changes

- **Google Play goes public:** in `finnsana/index.html`, replace the "Coming soon" `<span>` with the
  commented-out "Get it on Google Play" link just above it.
- **Screenshots:** save five 9:16 phone screenshots as `assets/finnsana-screen-1.png` … `-5.png` and
  replace each `<!-- SCREENSHOT n -->` comment with the `<img>` tag written inside it (add real alt text).
- **App icon:** replace `assets/finnsana-icon.png` with the real 512×512 icon (same name).
- **Privacy policy:** move the full text into `finnsana/privacy/index.html` and point the links on
  `finnsana/index.html` to `/finnsana/privacy/`.
- **New app:** copy the `finnsana/` folder to a new folder, edit the text, add a card in `index.html` and
  the new URLs in `sitemap.xml`.
