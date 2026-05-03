# Custom Image Storage Logic

This project uses a custom image storage flow for restaurant media instead of Laravel's default `public` disk. The custom flow is used for restaurant logos, category images, and menu item images.

The goal is:

- Store uploaded images outside the Laravel app, under `ROOT_MEDIA_DIR`.
- Convert uploaded images to WebP before saving.
- Save only the relative media path in the database.
- Fetch public image URLs by combining `ROOT_MEDIA_URL` with the saved relative path.

## Configuration

The custom disk is configured in `config/filesystems.php` as `media`.

```php
'media' => [
    'driver'     => 'local',
    'root'       => env('ROOT_MEDIA_DIR', storage_path('app/media')),
    'url'        => env('ROOT_MEDIA_URL', env('APP_URL') . '/media'),
    'visibility' => 'public',
    'throw'      => false,
],
```

Add these values to `.env` for each environment:

```env
ROOT_MEDIA_DIR=C:\wamp64\www\github\waas-metomenu-media
ROOT_MEDIA_URL=http://localhost/waas-metomenu-media
```

On a server, point `ROOT_MEDIA_DIR` to the physical folder where images should be written, and point `ROOT_MEDIA_URL` to the public URL that serves the same folder.

## Fetching Images

Use the global `media_url()` helper from `app/helpers.php`.

```php
media_url($restaurant->logo)
media_url($category->image)
media_url($menuItem->image)
```

The database stores a relative path like:

```text
5/logo/5_20260314095414.webp
```

`media_url()` converts it into:

```text
{ROOT_MEDIA_URL}/5/logo/5_20260314095414.webp
```

Example Blade usage:

```blade
@if($restaurant->logo)
    <img src="{{ media_url($restaurant->logo) }}" alt="{{ $restaurant->name }}">
@endif
```

For menu item theme views, prefer the accessor:

```blade
@if($item->image_url)
    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
@endif
```

`MenuItem::image_url` checks the related WebP image record first, verifies the file exists on the media disk, then falls back to `menu_items.image`.

## Saving Images

Use `App\Services\ImageConversionService`.

```php
use App\Services\ImageConversionService;
```

The generic save method is:

```php
$imageService = new ImageConversionService();

$path = $imageService->convertAndStore(
    $request->file('image'),
    "{$restaurantId}/category",
    "{$restaurantId}_{$categoryId}_{$imageService->istTimestamp()}"
);
```

This method:

1. Moves the uploaded file to a temporary file.
2. Reads the image with GD.
3. Converts it to WebP.
4. Writes it under `ROOT_MEDIA_DIR`.
5. Returns the relative path to store in the database.

The returned path looks like:

```text
5/category/5_12_20260314095414.webp
```

Store that relative path in the relevant table column:

```php
$category->update([
    'image' => $path,
]);
```

## Category Image Implementation

Category images are saved in `Admin\CategoryController`.

Directory format:

```text
{restaurantId}/category
```

Filename format:

```text
{restaurantId}_{categoryId}_{IST timestamp}.webp
```

Implementation pattern:

```php
if ($request->hasFile('image')) {
    $imageService = new ImageConversionService();
    $timestamp = $imageService->istTimestamp();
    $filename = "{$restaurantId}_{$category->category_id}_{$timestamp}";

    $imagePath = $imageService->convertAndStore(
        $request->file('image'),
        "{$restaurantId}/category",
        $filename
    );

    $category->update(['image' => $imagePath]);
}
```

## Restaurant Logo Implementation

Restaurant logos are saved in `Admin\SettingsController`.

Directory format:

```text
{restaurantId}/logo
```

Filename format:

```text
{restaurantId}_{IST timestamp}.webp
```

Implementation pattern:

```php
if ($request->hasFile('logo')) {
    $imageService = new ImageConversionService();
    $timestamp = $imageService->istTimestamp();
    $filename = "{$restaurant->rid}_{$timestamp}";

    $validated['logo'] = $imageService->convertAndStore(
        $request->file('logo'),
        "{$restaurant->rid}/logo",
        $filename
    );
}
```

## Menu Item Image Implementation

Menu item images use a cropped base64 data URL from the form, then convert that temporary image to WebP.

Controller input:

```php
'image_cropped' => 'nullable|string',
```

Save flow:

```php
$imageCropped = $validated['image_cropped'] ?? null;
unset($validated['image_cropped']);

$menuItem = MenuItem::create($validated);

if ($imageCropped) {
    $tmpFile = $this->decodeCroppedImage($imageCropped);

    if ($tmpFile) {
        $imageService = new ImageConversionService();

        $result = $imageService->processAndSaveImage(
            $tmpFile,
            $menuItem->mid,
            $restaurant->rid,
            $menuItem->name
        );

        $menuItem->update(['image' => $result['webp_path']]);
    }
}
```

`processAndSaveImage()` stores files under:

```text
{restaurantId}/menu-items/{restaurantId}_{menuItemId}_{IST timestamp}.webp
```

It also records the WebP path in `item_images_webp`:

```php
[
    'menu_item_id' => $menuItemId,
    'webp_path' => $webpPath,
]
```

Only one WebP record is kept per menu item. When a new image is saved, the old `item_images_webp` row is deleted before the new row is created.

## Deleting/Replacing Images

When replacing a custom media image, delete the old physical file from the media root before saving the new one.

```php
if ($category->image) {
    $mediaDiskRoot = config('filesystems.disks.media.root');
    $oldPath = $mediaDiskRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $category->image);

    if (file_exists($oldPath)) {
        @unlink($oldPath);
    }
}
```

For menu items, use:

```php
$imageService = new ImageConversionService();
$imageService->deleteOldImages($menuItem->mid);
```

That deletes the physical WebP file from `ROOT_MEDIA_DIR` and removes the `item_images_webp` database row.

## Important Notes

- Save only relative paths in the database.
- Do not save full URLs in database columns.
- Use `media_url()` when rendering custom media.
- Use the `media` disk root or `config('filesystems.disks.media.root')` when deleting custom media files.
- Do not use `Storage::disk('public')` for files saved with this custom media logic.
- The conversion service depends on PHP GD WebP support through `imagewebp()`.
