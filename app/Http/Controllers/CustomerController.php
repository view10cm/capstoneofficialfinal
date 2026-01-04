<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\MenuProduct;
use App\Models\OrderToStaffTransaction;
use App\Models\VoiceTranscript;
use App\Models\UtteranceGallery;

class CustomerController extends Controller
{
    /**
     * Display customer landing page
     */
    public function landingPage()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerLandingPage');
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customerDashboard');
    }

    /**
     * Display customer notification page
     */
    public function notification()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerNotification');
    }

    /**
     * Display customer order area page with menu products
     */
    public function orderArea()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        // Get initial products (default: pork subcategory from main course)
        $products = MenuProduct::where('menuStatus', 'Available')
            ->where('menuCategory', 'main-course')
            ->where('menuSubcategory', 'pork')
            ->orderBy('menuName')
            ->get();
        
        // Group products into slides of 6 items each
        $slides = $products->chunk(6);
        
        return view('customer.customerOrderArea', compact('slides', 'products'));
    }

    /**
     * Get products by category and subcategory (AJAX)
     */
    public function getProductsByCategory(Request $request)
    {
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');

        // Map UI subcategory names to database values
        $subcategoryMap = [
            'Pork' => 'pork',
            'Chicken' => 'chicken',
            'Beef' => 'beef',
            'Fish & Seafood' => 'fish-seafood',
            'Pasta' => 'pasta',
            'Noodles' => 'noodles',
            'Salads' => 'salads',
            'Knick/Knacks' => 'knick-knacks',
            'Sandwiches' => 'sandwiches',
            'Hot' => 'hot',
            'Iced' => 'iced',
            'Frappe' => 'frappe',
            'Milktea' => 'milktea'
        ];

        $dbSubcategory = $subcategoryMap[$subcategory] ?? strtolower(str_replace(' ', '-', $subcategory));

        $products = MenuProduct::where('menuStatus', 'Available')
            ->where('menuCategory', $category)
            ->where('menuSubcategory', $dbSubcategory)
            ->orderBy('menuName')
            ->get();

        // Group products into slides of 6 items each
        $slides = $products->chunk(6);
        
        // Get total number of slides
        $totalSlides = count($slides);
        
        // Return ALL slides data
        $slidesHtml = [];
        foreach ($slides as $slideIndex => $slideProducts) {
            $slidesHtml[] = view('partials.product-slide', [
                'products' => $slideProducts,
                'slideIndex' => $slideIndex,
                'totalSlides' => $totalSlides
            ])->render();
        }

        return response()->json([
            'products' => $products,
            'slides' => $slidesHtml,
            'totalSlides' => $totalSlides
        ]);
    }

    /**
     * Get specific slide content (for arrow navigation)
     */
    public function getSlideContent(Request $request)
    {
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');
        $slideIndex = $request->input('slideIndex', 0);

        // Map UI subcategory names to database values
        $subcategoryMap = [
            'Pork' => 'pork',
            'Chicken' => 'chicken',
            'Beef' => 'beef',
            'Fish & Seafood' => 'fish-seafood',
            'Pasta' => 'pasta',
            'Noodles' => 'noodles',
            'Salads' => 'salads',
            'Knick/Knacks' => 'knick-knacks',
            'Sandwiches' => 'sandwiches',
            'Hot' => 'hot',
            'Iced' => 'iced',
            'Frappe' => 'frappe',
            'Milktea' => 'milktea'
        ];

        $dbSubcategory = $subcategoryMap[$subcategory] ?? strtolower(str_replace(' ', '-', $subcategory));

        $products = MenuProduct::where('menuStatus', 'Available')
            ->where('menuCategory', $category)
            ->where('menuSubcategory', $dbSubcategory)
            ->orderBy('menuName')
            ->get();

        // Group products into slides of 6 items each
        $slides = $products->chunk(6);
        
        // Get total number of slides
        $totalSlides = count($slides);
        
        // Validate slide index
        $slideIndex = min($slideIndex, $totalSlides - 1);
        $slideIndex = max($slideIndex, 0);
        
        // Get products for requested slide
        $slideProducts = isset($slides[$slideIndex]) ? $slides[$slideIndex] : collect([]);

        return response()->json([
            'html' => view('partials.product-slide', [
                'products' => $slideProducts,
                'slideIndex' => $slideIndex,
                'totalSlides' => $totalSlides
            ])->render(),
            'slideIndex' => $slideIndex,
            'totalSlides' => $totalSlides
        ]);
    }

    /**
     * Get product details by name
     */
    public function getProductByName(Request $request)
    {
        try {
            $productName = $request->input('productName');
            
            $product = MenuProduct::where('menuName', 'like', '%' . $productName . '%')
                ->where('menuStatus', 'Available')
                ->first();
            
            if ($product) {
                return response()->json([
                    'success' => true,
                    'product' => [
                        'name' => $product->menuName,
                        'price' => $product->menuPrice,
                        'category' => $product->menuCategory,
                        'subcategory' => $product->menuSubcategory,
                        'image' => $product->menuImage ? asset('storage/' . $product->menuImage) : null
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting product by name: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error finding product'
            ], 500);
        }
    }

    /**
     * Save order to staff transaction table
     */
    public function saveOrderToStaffTransaction(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'paymentNumber' => 'required|integer|min:1|max:20',
            'orderType' => 'required|in:dine-in,takeout',
            'orderPaymentMethod' => 'required|in:cash,electronic',
            'orderNotes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.productName' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.totalProductPrice' => 'required|numeric|min:0',
            'items.*.totalProductTax' => 'required|numeric|min:0',
        ]);
        
        try {
            // Generate order ID (use same ID for all items in this order)
            $orderID = OrderToStaffTransaction::generateOrderID();
            
            // Save each item as a separate record with SAME orderID
            foreach ($validated['items'] as $item) {
                $transaction = new OrderToStaffTransaction();
                $transaction->orderID = $orderID; // Same orderID for all items
                $transaction->paymentNumber = $validated['paymentNumber'];
                $transaction->orderType = $validated['orderType'];
                $transaction->orderPaymentMethod = $validated['orderPaymentMethod'];
                $transaction->orderProductName = $item['productName'];
                $transaction->orderQuantity = $item['quantity'];
                $transaction->orderTotalProductPrice = $item['totalProductPrice'];
                $transaction->orderTotalProductTax = $item['totalProductTax'];
                $transaction->orderNotes = $validated['orderNotes'] ?? null;
                $transaction->orderCreateDateAndTime = now();
                $transaction->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order saved successfully!',
                'orderID' => $orderID
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Match transcribed utterance with menu items
     */
    public function matchUtterance(Request $request)
    {
        try {
            $transcript = strtolower(trim($request->input('transcript')));
            
            // Remove common filler words and normalize
            $transcript = $this->normalizeTranscript($transcript);
            
            // First, try exact match or contains match
            $exactMatches = UtteranceGallery::where('acceptedUtterance', 'LIKE', "%{$transcript}%")
                ->orWhere('acceptedUtterance', $transcript)
                ->get();
            
            if ($exactMatches->count() > 0) {
                // Get the first match
                $match = $exactMatches->first();
                
                // Get product details from menu_products table
                $product = MenuProduct::where('menuName', $match->menuItem)
                    ->where('menuStatus', 'Available')
                    ->first();
                
                $response = [
                    'success' => true,
                    'matchedMenuItem' => $match->menuItem,
                    'confidence' => 'Confident', // Exact matches are always Confident
                    'matchType' => 'exact'
                ];
                
                // Add product details if found
                if ($product) {
                    $response['product'] = [
                        'name' => $product->menuName,
                        'price' => $product->menuPrice,
                        'category' => $product->menuCategory,
                        'image' => $product->menuImage ? asset('storage/' . $product->menuImage) : null
                    ];
                }
                
                return response()->json($response);
            }
            
            // If no direct match, try fuzzy matching
            $allUtterances = UtteranceGallery::all();
            $bestMatch = null;
            $highestSimilarity = 0;
            
            foreach ($allUtterances as $utterance) {
                $utteranceText = strtolower($utterance->acceptedUtterance);
                $utteranceText = $this->normalizeTranscript($utteranceText);
                
                // Calculate similarity
                $similarity = $this->calculateSimilarity($transcript, $utteranceText);
                
                // Also check if transcript contains key words from menu item
                $menuItemWords = explode(' ', strtolower($utterance->menuItem));
                $wordMatchCount = 0;
                foreach ($menuItemWords as $word) {
                    if (strlen($word) > 2 && strpos($transcript, $word) !== false) {
                        $wordMatchCount++;
                    }
                }
                
                // Boost similarity if we have word matches
                if ($wordMatchCount > 0) {
                    $similarity += ($wordMatchCount * 0.1);
                }
                
                if ($similarity > $highestSimilarity) {
                    $highestSimilarity = $similarity;
                    $bestMatch = $utterance;
                }
            }
            
            if ($bestMatch) {
                // LOWERED CONFIDENCE THRESHOLDS:
                // Confident ≥ 80%, Partially Confident ≥ 60%, Not Confident ≥ 40%
                $confidence = 'Not Confident';
                if ($highestSimilarity >= 0.8) { // Lowered from 0.9 to 0.8
                    $confidence = 'Confident';
                } elseif ($highestSimilarity >= 0.6) { // Lowered from 0.7 to 0.6
                    $confidence = 'Partially Confident';
                } elseif ($highestSimilarity >= 0.4) { // Lowered from 0.5 to 0.4
                    $confidence = 'Not Confident';
                } else {
                    // Below 40% similarity, don't return a match
                    return response()->json([
                        'success' => false,
                        'message' => 'No matching menu item found (similarity too low)',
                        'similarity' => $highestSimilarity
                    ]);
                }
                
                // Get product details from menu_products table
                $product = MenuProduct::where('menuName', $bestMatch->menuItem)
                    ->where('menuStatus', 'Available')
                    ->first();
                
                $response = [
                    'success' => true,
                    'matchedMenuItem' => $bestMatch->menuItem,
                    'confidence' => $confidence,
                    'similarity' => $highestSimilarity,
                    'matchType' => 'fuzzy'
                ];
                
                // Add product details if found
                if ($product) {
                    $response['product'] = [
                        'name' => $product->menuName,
                        'price' => $product->menuPrice,
                        'category' => $product->menuCategory,
                        'image' => $product->menuImage ? asset('storage/' . $product->menuImage) : null
                    ];
                }
                
                return response()->json($response);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No matching menu item found'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error matching utterance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error matching utterance: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Normalize transcript text
     */
    private function normalizeTranscript($text)
    {
        // Remove common filler words
        $fillerWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by'];
        $words = explode(' ', $text);
        $filteredWords = array_filter($words, function($word) use ($fillerWords) {
            return !in_array($word, $fillerWords) && strlen($word) > 0;
        });
        
        // Remove punctuation and extra spaces
        $normalized = implode(' ', $filteredWords);
        $normalized = preg_replace('/[^a-z0-9\s]/', '', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        
        return trim($normalized);
    }
    
    /**
     * Calculate similarity between two strings
     */
    private function calculateSimilarity($str1, $str2)
    {
        // Remove non-alphanumeric characters
        $str1 = preg_replace('/[^a-z0-9]/', '', $str1);
        $str2 = preg_replace('/[^a-z0-9]/', '', $str2);
        
        // Use levenshtein distance for short strings
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $maxLen = max($len1, $len2);
        
        if ($maxLen == 0) return 0;
        
        $distance = levenshtein($str1, $str2);
        $similarity = 1 - ($distance / $maxLen);
        
        return max(0, min(1, $similarity));
    }

    /**
     * Save voice transcript
     */
    public function saveVoiceTranscript(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'transcribedData' => 'required|string|max:1000',
                'matchedMenuItem' => 'nullable|string',
                'confidenceLevel' => 'nullable|in:Not Confident,Partially Confident,Confident',
            ]);
            
            \Log::info('Saving voice transcript:', $validated);
            
            // Create new voice transcript record
            $transcript = new VoiceTranscript();
            $transcript->transcribedData = $validated['transcribedData'];
            
            // Add matched menu item if available
            if (isset($validated['matchedMenuItem'])) {
                $transcript->matchedMenuItem = $validated['matchedMenuItem'];
            }
            
            // Add confidence level
            if (isset($validated['confidenceLevel'])) {
                $transcript->confidence_level = $validated['confidenceLevel'];
            } else {
                $transcript->confidence_level = 'Not Confident';
            }
            
            $transcript->save();
            
            \Log::info('Transcript saved successfully with ID: ' . $transcript->voiceID);
            
            // AUTO-ADD TO UTTERANCE GALLERY FOR CONFIDENT OR PARTIALLY CONFIDENT MATCHES
            $addedToGallery = false;
            if (isset($validated['matchedMenuItem']) && 
                isset($validated['confidenceLevel']) &&
                in_array($validated['confidenceLevel'], ['Confident', 'Partially Confident'])) {
                
                \Log::info('Attempting to add to utterance gallery:', [
                    'menuItem' => $validated['matchedMenuItem'],
                    'utterance' => $validated['transcribedData'],
                    'confidence' => $validated['confidenceLevel']
                ]);
                
                $addedToGallery = $this->addToUtteranceGallery(
                    $validated['matchedMenuItem'],
                    $validated['transcribedData'],
                    $validated['confidenceLevel']
                );
                
                \Log::info('Add to gallery result: ' . ($addedToGallery ? 'Success' : 'Failed or already exists'));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Transcript saved successfully',
                'voiceID' => $transcript->voiceID,
                'confidenceLevel' => $transcript->confidence_level,
                'addedToGallery' => $addedToGallery
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving voice transcript: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save transcript: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add utterance to gallery
     */
    private function addToUtteranceGallery($menuItem, $utterance, $confidenceLevel)
    {
        try {
            \Log::info('Adding to utterance gallery:', [
                'menuItem' => $menuItem,
                'utterance' => $utterance,
                'confidence' => $confidenceLevel
            ]);
            
            // Normalize the utterance
            $normalizedUtterance = $this->normalizeUtteranceForGallery($utterance);
            
            \Log::info('Normalized utterance: ' . $normalizedUtterance);
            
            // Check if this exact utterance already exists for this menu item
            $existing = UtteranceGallery::where('menuItem', $menuItem)
                ->where('acceptedUtterance', $normalizedUtterance)
                ->first();
            
            if ($existing) {
                \Log::info('Utterance already exists in gallery:', [
                    'menuItem' => $menuItem,
                    'utterance' => $normalizedUtterance,
                    'transcriptionID' => $existing->transcriptionID
                ]);
                return false; // Already exists
            }
            
            // Add new utterance to gallery
            $galleryEntry = new UtteranceGallery();
            $galleryEntry->menuItem = $menuItem;
            $galleryEntry->acceptedUtterance = $normalizedUtterance;
            
            \Log::info('Creating new gallery entry:', [
                'menuItem' => $galleryEntry->menuItem,
                'acceptedUtterance' => $galleryEntry->acceptedUtterance
            ]);
            
            $galleryEntry->save();
            
            \Log::info('Successfully added new utterance to gallery:', [
                'transcriptionID' => $galleryEntry->transcriptionID,
                'menuItem' => $galleryEntry->menuItem,
                'utterance' => $galleryEntry->acceptedUtterance,
                'confidence' => $confidenceLevel
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error('Error adding to utterance gallery: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Normalize utterance for gallery
     */
    private function normalizeUtteranceForGallery($utterance)
    {
        // Convert to lowercase
        $normalized = strtolower(trim($utterance));
        
        // Remove extra whitespace
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        
        // Remove common filler words but keep the utterance mostly intact
        $fillerWords = ['um', 'uh', 'like', 'you know', 'i mean', 'so', 'well', 'actually', 'basically'];
        foreach ($fillerWords as $filler) {
            $normalized = str_replace($filler, '', $normalized);
        }
        
        // Remove any double spaces created by removing filler words
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        
        // Remove any leading/trailing spaces that may have been created
        $normalized = trim($normalized);
        
        return $normalized;
    }
}