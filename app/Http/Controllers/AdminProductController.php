<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Categorie as Category;
use App\Models\Brand;
use App\Models\Variant as ProductVariant;
use App\Models\ProductBuyLink;
use App\Models\ProductMedia;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        // Query products dengan relasi
        $query = Product::with(['category', 'brand', 'variants'])->latest();

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter berdasarkan stok
        if ($request->has('stock') && $request->stock != '') {
            switch ($request->stock) {
                case 'high':
                    $query->where('stock_quantity', '>', 50);
                    break;
                case 'medium':
                    $query->whereBetween('stock_quantity', [10, 50]);
                    break;
                case 'low':
                    $query->where('stock_quantity', '<', 10);
                    break;
                case 'out':
                    $query->where('stock_quantity', 0);
                    break;
            }
        }

        // Sorting
        if ($request->has('sort') && $request->sort != '') {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'price-low':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('base_price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('sku', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $products = $query->paginate(12);

        // Get categories for filter dropdown
        $categories = Category::where('is_active', true)->get();

        return view('admin-produk.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('admin-produk.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        // Validasi data utama produk
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_draft' => 'boolean',

            // Validasi untuk variants
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.type' => 'required|string|in:color,size,material,edition,custom',
            'variants.*.value' => 'required|string|max:255',
            'variants.*.sku' => 'nullable|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:10240',

            // Validasi untuk buy links
            'buy_links' => 'required|array|min:1',
            'buy_links.*.platform' => 'required|string|max:100',
            'buy_links.*.custom_platform' => 'nullable|string|max:100',
            'buy_links.*.url' => 'required|url|max:500',
            'buy_links.*.price' => 'nullable|numeric|min:0',

            // Validasi untuk media tambahan
            'additional_media' => 'nullable|array',
            'additional_media.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:10240',
        ]);

        DB::beginTransaction();

        try {
            // Generate slug dari nama produk
            $slug = $this->generateUniqueSlug($request->name);

            // Handle upload main image
            $mainImagePath = null;
            if ($request->hasFile('main_image')) {
                $mainImagePath = $this->uploadFile($request->file('main_image'), 'products/main');
            }

            // Create main product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'sku' => $validated['sku'] ?? $this->generateSKU(),
                'stock_quantity' => $validated['stock_quantity'],
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? 10,
                'weight' => $validated['weight'] ?? null,
                'dimensions' => $validated['dimensions'] ?? null,
                'meta_title' => $validated['meta_title'] ?? $validated['name'],
                'meta_description' => $validated['meta_description'] ?? Str::limit(strip_tags($validated['description']), 160),
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'main_image' => $mainImagePath,
                'is_draft' => $request->has('is_draft'),
                'published_at' => $request->has('is_draft') ? null : now(),
            ]);

            // Process variants
            $this->processVariants($product, $request->variants, $deletedVariants ?? []);

            // Process buy links
            $this->processBuyLinks($product, $request->buy_links, $deletedLinks ?? []);

            // Process additional media
            if ($request->hasFile('additional_media')) {
                $this->processAdditionalMedia($product, $request->file('additional_media'));
            }

            DB::commit();

            $message = $request->has('is_draft') ? 'Produk berhasil disimpan sebagai draft!' : 'Produk berhasil dipublikasikan!';

            return redirect('/admin/produk')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded files if error occurs
            if (isset($mainImagePath)) {
                Storage::delete($mainImagePath);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Process and save product variants
     */
    /**
     * Process and save product variants - SOLUSI SEDERHANA
     */
 private function processVariants(Product $product, array $variants, ?array $deletedVariants = null)
{
    // Hapus variants yang dipilih
    if (!empty($deletedVariants)) {
        ProductVariant::whereIn('id', $deletedVariants)->delete();
    }

    foreach ($variants as $index => $variantInput) {
        $variantImagePath = null;
        $variantVideoPath = null;
        $filePath = null;

        // Handle variant file upload
        if (isset($variantInput['file']) && $variantInput['file']->isValid()) {
            $file = $variantInput['file'];
            $uploadPath = 'products/variants';

            if (str_starts_with($file->getMimeType(), 'image/')) {
                $variantImagePath = $this->uploadFile($file, $uploadPath);
            } elseif (str_starts_with($file->getMimeType(), 'video/')) {
                $variantVideoPath = $this->uploadFile($file, $uploadPath);
            } else {
                $filePath = $this->uploadFile($file, $uploadPath);
            }
        }

        // Prepare additional data
        $additionalData = $this->prepareVariantAdditionalData($variantInput);
        if (is_array($additionalData)) {
            $additionalData = json_encode($additionalData);
        }

        // Siapkan data varian untuk DB
        $variantData = [
            'product_id'          => $product->id,
            'name'                => $variantInput['name'],
            'type'                => $variantInput['type'],
            'value'               => $variantInput['value'],
            'sku'                 => $variantInput['sku'] ?? $this->generateVariantSKU($product->sku, $index),
            'price'               => $variantInput['price'],
            'sale_price'          => $variantInput['sale_price'] ?? null,
            'stock_quantity'      => $variantInput['stock'],
            'low_stock_threshold' => $variantInput['low_stock_threshold'] ?? 5,
            'order'               => $index,
            'additional_data'     => $additionalData,
            'is_active'           => $variantInput['is_active'] ?? true,
        ];

        // Tambahkan path file jika ada upload baru
        if ($variantImagePath) {
            $variantData['image'] = $variantImagePath;
        }
        if ($variantVideoPath) {
            $variantData['video'] = $variantVideoPath;
        }
        if ($filePath) {
            $variantData['file_path'] = $filePath;
        }

        // Update atau create variant
        if (!empty($variantInput['id'])) {
            $variant = ProductVariant::find($variantInput['id']);
            if ($variant) {
                // kalau SKU bentrok, generate baru
                if (ProductVariant::where('sku', $variantData['sku'])->where('id', '!=', $variant->id)->exists()) {
                    $variantData['sku'] = $this->generateVariantSKU($product->sku, $index);
                }
                $variant->update($variantData);
            }
        } else {
            // kalau SKU sudah dipakai, generate baru
            if (ProductVariant::where('sku', $variantData['sku'])->exists()) {
                $variantData['sku'] = $this->generateVariantSKU($product->sku, $index);
            }
            ProductVariant::create($variantData);
        }
    }
}


    /**
     * Process and save buy links
     */
    /**
     * Process and save buy links
     */
    private function processBuyLinks(Product $product, array $buyLinks, array $deletedLinks)
    {
        // Hapus links yang dipilih
        if (!empty($deletedLinks)) {
            ProductBuyLink::whereIn('id', $deletedLinks)->delete();
        }

        foreach ($buyLinks as $index => $linkData) {
            $platformName = $linkData['platform'] === 'other' ? $linkData['custom_platform'] ?? 'Other' : $linkData['platform'];

            $metaData = json_encode([
                'platform_name' => $platformName,
                'updated_at' => now()->toISOString(),
            ]);

            $buyLinkData = [
                'product_id' => $product->id,
                'variant_id' => null,
                'platform' => $linkData['platform'],
                'custom_platform' => $linkData['custom_platform'] ?? null,
                'url' => $linkData['url'],
                'price' => $linkData['price'] ?? $product->base_price,
                'order' => $index,
                'meta_data' => $metaData,
                'is_active' => $linkData['is_active'] ?? true,
            ];

            // Update atau create buy link
            if (isset($linkData['id']) && $linkData['id']) {
                $buyLink = ProductBuyLink::find($linkData['id']);
                if ($buyLink) {
                    $buyLink->update($buyLinkData);
                }
            } else {
                ProductBuyLink::create($buyLinkData);
            }
        }
    }

    /**
     * Process additional media files
     */
    private function processAdditionalMedia(Product $product, array $mediaFiles)
    {
        foreach ($mediaFiles as $index => $file) {
            if ($file->isValid()) {
                $filePath = $this->uploadFile($file, 'products/media');
                $fileType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';

                ProductMedia::create([
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'mime_type' => $file->getMimeType(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'order' => $index,
                    'is_main' => false,
                ]);
            }
        }
    }

    /**
     * Process variant-specific attributes
     */
    private function processVariantAttributes(ProductVariant $variant, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!empty($value)) {
                ProductAttribute::create([
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'key' => $key,
                    'value' => $value,
                    'order' => 0,
                ]);
            }
        }
    }

    /**
     * Prepare additional data for variant
     */
    private function prepareVariantAdditionalData(array $variantData)
    {
        $additionalData = [];

        // Include any additional fields that might be useful
        if (isset($variantData['color_code'])) {
            $additionalData['color_code'] = $variantData['color_code'];
        }
        if (isset($variantData['size_chart'])) {
            $additionalData['size_chart'] = $variantData['size_chart'];
        }
        if (isset($variantData['material_composition'])) {
            $additionalData['material_composition'] = $variantData['material_composition'];
        }

        $additionalData['created_via'] = 'admin_panel';
        $additionalData['created_at'] = now()->toISOString();

        return !empty($additionalData) ? $additionalData : null;
    }

    /**
     * Generate unique slug for product
     */
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Generate unique SKU for product
     */
    private function generateSKU()
    {
        do {
            $sku = 'PROD-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * Generate SKU for variant
     */
    private function generateVariantSKU($productSku, $variantIndex)
    {
        if ($productSku) {
            return $productSku . '-V' . ($variantIndex + 1);
        }

        do {
            $sku = 'VAR-' . strtoupper(Str::random(6)) . '-' . ($variantIndex + 1);
        } while (ProductVariant::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * Handle file upload with proper naming
     */
    private function uploadFile($file, $directory)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . Str::random(10) . '.' . $extension;

        return $file->storeAs($directory, $fileName, 'public');
    }

    /**
     * Get variant-specific buy links (if needed in future)
     */
    private function processVariantBuyLinks(ProductVariant $variant, array $buyLinks)
    {
        foreach ($buyLinks as $index => $linkData) {
            if (isset($linkData['variant_specific']) && $linkData['variant_specific']) {
                $platformName = $linkData['platform'] === 'other' ? $linkData['custom_platform'] ?? 'Other' : $linkData['platform'];

                ProductBuyLink::create([
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'platform' => $linkData['platform'],
                    'custom_platform' => $linkData['custom_platform'] ?? null,
                    'url' => $linkData['url'],
                    'price' => $linkData['price'] ?? $variant->price,
                    'order' => $index,
                    'meta_data' => [
                        'platform_name' => $platformName,
                        'variant_specific' => true,
                        'created_at' => now()->toISOString(),
                    ],
                ]);
            }
        }
    }

    private function getStockIndicator($stock)
    {
        if ($stock > 50) {
            return 'stock-high';
        }
        if ($stock >= 10) {
            return 'stock-medium';
        }
        if ($stock > 0) {
            return 'stock-low';
        }
        return 'stock-low'; // untuk stok 0
    }

    private function getStockBadge($stock)
    {
        if ($stock == 0) {
            return ['class' => 'badge-out-of-stock', 'text' => 'Habis'];
        }
        if ($stock < 10) {
            return ['class' => 'badge-low-stock', 'text' => 'Stok Menipis'];
        }
        if ($stock <= 50) {
            return ['class' => '', 'text' => ''];
        }
        return ['class' => 'badge-new', 'text' => 'Stok Banyak'];
    }

    private function getTotalSold($product)
    {
        // Jika ada logic penjualan, bisa dihitung dari order items
        // Untuk sementara, kita gunakan random number atau field tertentu
        return rand(50, 500); // Contoh random
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'brand', 'variants', 'buyLinks', 'media'])->findOrFail($id);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('admin-produk.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_draft' => 'boolean',

            // Validasi untuk variants
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:variants,id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.type' => 'required|string|in:color,size,material,edition,custom',
            'variants.*.value' => 'required|string|max:255',
            'variants.*.sku' => 'nullable|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:10240',
            'variants.*.is_active' => 'boolean',

            // Validasi untuk buy links
            'buy_links' => 'required|array|min:1',
            'buy_links.*.id' => 'nullable|exists:product_buy_links,id',
            'buy_links.*.platform' => 'required|string|max:100',
            'buy_links.*.custom_platform' => 'nullable|string|max:100',
            'buy_links.*.url' => 'required|url|max:500',
            'buy_links.*.price' => 'nullable|numeric|min:0',
            'buy_links.*.is_active' => 'boolean',

            // Validasi untuk media tambahan
            'additional_media' => 'nullable|array',
            'additional_media.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:10240',

            // Media yang akan dihapus
            'deleted_media' => 'nullable|array',
            'deleted_variants' => 'nullable|array',
            'deleted_buy_links' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Update slug jika nama berubah
            if ($product->name != $request->name) {
                $slug = $this->generateUniqueSlug($request->name);
            } else {
                $slug = $product->slug;
            }

            // Handle upload main image jika ada
            $mainImagePath = $product->main_image;
            if ($request->hasFile('main_image')) {
                // Hapus gambar lama
                if ($product->main_image) {
                    Storage::delete($product->main_image);
                }
                $mainImagePath = $this->uploadFile($request->file('main_image'), 'products/main');
            }

            // Update main product
            $product->update([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'sku' => $validated['sku'] ?? $product->sku,
                'stock_quantity' => $validated['stock_quantity'],
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? 10,
                'weight' => $validated['weight'] ?? null,
                'dimensions' => $validated['dimensions'] ?? null,
                'meta_title' => $validated['meta_title'] ?? $validated['name'],
                'meta_description' => $validated['meta_description'] ?? Str::limit(strip_tags($validated['description']), 160),
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'main_image' => $mainImagePath,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'is_draft' => $request->boolean('is_draft', false),
                'published_at' => $request->boolean('is_draft') ? null : $product->published_at ?? now(),
            ]);

            // Process variants (update, create, delete)
            $this->processVariants($product, $request->variants, $request->deleted_variants ?? []);

            // Process buy links (update, create, delete)
            $this->processBuyLinks($product, $request->buy_links, $request->deleted_buy_links ?? []);

            // Process additional media
            if ($request->hasFile('additional_media')) {
                $this->processAdditionalMedia($product, $request->file('additional_media'));
            }

            // Hapus media yang dipilih
            if ($request->has('deleted_media')) {
                $this->deleteMedia($request->deleted_media ?? []);
            }

            DB::commit();

            return redirect('/admin/produk')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            // \Log::error('Product update failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Product::with(['variants', 'buyLinks', 'media'])->findOrFail($id);

            // Hapus file gambar utama
            if ($product->main_image) {
                Storage::delete($product->main_image);
            }

            // Hapus file media tambahan
            foreach ($product->media as $media) {
                Storage::delete($media->file_path);
            }

            // Hapus file variants
            foreach ($product->variants as $variant) {
                if ($variant->image) {
                    Storage::delete($variant->image);
                }
                if ($variant->video) {
                    Storage::delete($variant->video);
                }
                if ($variant->file_path) {
                    Storage::delete($variant->file_path);
                }
            }

            // Hapus data dari database
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            

            // \Log::error('Product deletion failed: ' . $e->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus produk!',
                ],
                500,
            );
        }
    }

    private function deleteMedia(array $mediaIds)
    {
        $mediaItems = ProductMedia::whereIn('id', $mediaIds)->get();

        foreach ($mediaItems as $media) {
            Storage::delete($media->file_path);
            $media->delete();
        }
    }
}
