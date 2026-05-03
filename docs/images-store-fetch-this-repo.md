Listed directory gecnoguru-career-portal
Listed directory public
Listed directory public
Listed directory landing
Listed directory assets
Listed directory images
Listed directory img
Searched for "<img"
Searched for "(Storage::|->store\(|->move\()"
Searched for "url\(.*?\.(png|jpg|jpeg|gif|webp|svg)"
Listed directory Models
Listed directory Controllers
Searched for "(\w|-|/)+\.(png|jpg|jpeg|gif|webp|svg|ico)"
Searched for "(\w|-|/)+\.(png|jpg|jpeg|gif|webp|svg|ico)"
Searched for "(\w|-|/)+\.(png|jpg|jpeg|gif|webp|svg|ico)"
Searched for "(\w|-|/)+\.(png|jpg|jpeg|gif|webp|svg|ico)"
Searched for "url\(.*?\.(png|jpg|jpeg|gif|webp|svg)"
Searched for "url\(.*?\.(png|jpg|jpeg|gif|webp|svg)"
Listed directory css

I have analyzed the entire repository to identify all locations where images are stored, fetched, and exactly which images are being used. 

Here is the comprehensive breakdown of image management across your `gecnoguru-career-portal` repository:

### 1. Storing Locations (Where Images Are Kept)
Currently, images are purely **static assets** served from your public directory structures. The application **does not** feature any dynamic user-uploaded image systems (e.g., no `Storage::put()`, `$request->store()`, or `storage/app/public` usage). 

All local images are housed inside these directories:
* **`public/landing/assets/images/`** — Primary directory which is further organized into subfolders such as `brand`, `icons`, `user`, `shape`, `logo`, `product`.
* **`public/landing/assets/img/`** — Houses a single file (`hero.png`).

### 2. Fetching Locations (Where Images Are Loaded)
Images are fetched throughout your Blade templates under `resources/views` and the standalone scripts under `public/landing`. Below is the exact file map:

#### General Layouts & Authentication Flow
*   **`resources/views/index.blade.php`**
*   **`public/landing/index.php`**
    *   **Images Used:**
        *   `/landing/assets/images/gecnoguru-favicon.jpg` (Logo styling)
        *   `/landing/assets/images/shape/grid-01.svg` (Background styling texture)
        *   `/landing/assets/images/user/user-01.png`
        *   `/landing/assets/images/user/user-02.png`
        *   `/landing/assets/images/user/user-03.png` (Used as user/testimonial avatars)

#### Main Application Dashboard & User Views
*   **`resources/views/dashboard.blade.php`**
*   **`resources/views/profile.blade.php`**
    *   **Images Used:**
        *   `/landing/assets/images/gecnoguru-favicon.jpg` (Favicons and sidebar branding)

#### Session Booking Funnel
*   **`resources/views/session_booking/index.blade.php`**
*   **`resources/views/session_booking/create.blade.php`**
*   **`resources/views/session_booking/payment.blade.php`**
    *   **Images Used:**
        *   `/landing/assets/images/gecnoguru-favicon.jpg` (Loaded as structural logos & favicons)

#### Placeholder Modules
*   **`resources/views/placeholders/coming-soon.blade.php`**
*   **`resources/views/placeholders/whatsapp-redirect.blade.php`**
    *   **Images Used:**
        *   `/landing/assets/images/gecnoguru-favicon.jpg`

---

### 3. External Image Dependencies (Third-Party CDN URLs)
In some of your pages, instead of using locally stored images, you are pulling them directly via public CDN links over the internet.

**For Service Pages (`resources/views/services.blade.php`):**
*   **Web Developer Portfolio:** `https://www.hostinger.com/tutorials/.../web-developer-portfolio.webp`
*   **Career Coaching:** `https://leapcoaching.ie/.../Career-Coaching-scaled.jpeg`
*   **Interview Prep:** `https://mycvcreator.com/.../67ac6bdd720109.00510634.jpg`

**For Resume Template Selection (`resources/views/resume-template.blade.php`):**
The platform currently hotlinks 8 different template thumbnails from `resumebuilder.com`:
*   *Modern Elegance:* `.../Hybrid_Example-3.pdf.jpeg`
*   *Modern Professional:* `.../Graphic-Design-Mid-Level.pdf.jpeg`
*   *Minimal White:* `.../Basic-Mid-Level.pdf.jpeg`
*   *Classic Professional:* `.../Sales-Senior-Level.pdf.jpeg`
*   *Corporate Blue:* `.../Business-Senior-Level.pdf.jpeg`
*   *Timeline Style:* `.../System-Administrator-Senior-Level.pdf.jpeg`
*   *Modern Icons:* `.../Filmmaker-Senior-Level.pdf.jpeg`
*   *Classic Sidebar:* `.../Teacher-Senior-Level.pdf.jpeg`

### Summary
*   **Static Internal Logic:** All custom branding logic (logos, grids, user avatars) points seamlessly to `public/landing/assets/images/`.
*   **External Content Display:** The heavy graphics (thumbnails, service references) exist entirely mapped from HTTP URLs outside your server. 
*   **No Upload Mechanism:** There is nowhere in the backend (`Http/Controllers`) interacting with an S3 bucket or local system file storages to update or save new media files.

Let me know if you would like to swap those external CDN links into your local `public/images` folder so your site doesn't rely on third-party links not breaking!