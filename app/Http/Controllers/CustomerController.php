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
            
            // Extract order item from phrase
            $cleanedTranscript = $this->extractOrderItemFromPhrase($transcript);
            $transcript = $cleanedTranscript ?: $transcript;
            
            if (empty($transcript)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No clear menu item detected in your request',
                    'similarity' => 0
                ]);
            }
            
            // Check for ambiguous/general terms
            $ambiguousTerms = $this->getAmbiguousTerms();
            
            foreach ($ambiguousTerms as $term => $info) {
                // Check if the term appears in the transcript
                if (strpos($transcript, $term) !== false) {
                    // This is an ambiguous term, search for all matching products
                    $matchingProducts = $this->searchForProducts($term);
                    
                    if (count($matchingProducts) > 0) {
                        return response()->json([
                            'success' => true,
                            'matchedMenuItem' => null, // No specific item
                            'ambiguousTerm' => $term,
                            'termName' => $info['name'],
                            'matchingProducts' => $matchingProducts,
                            'message' => 'Multiple options found',
                            'confidence' => 'Ambiguous',
                            'matchType' => 'ambiguous'
                        ]);
                    }
                }
            }
            
            // First, try exact match or contains match
            $exactMatches = UtteranceGallery::where('acceptedUtterance', 'LIKE', "%{$transcript}%")
                ->orWhere('acceptedUtterance', $transcript)
                ->get();
            
            if ($exactMatches->count() > 0) {
                // Get the first match
                $match = $exactMatches->first();
                
                // Check if this is a valid exact match (not just partial)
                $similarity = $this->calculateSimilarity($transcript, strtolower($match->acceptedUtterance));
                
                return response()->json([
                    'success' => true,
                    'matchedMenuItem' => $match->menuItem,
                    'confidence' => $similarity >= 0.8 ? 'Confident' : 'Partially Confident',
                    'matchType' => 'exact',
                    'similarity' => $similarity
                ]);
            }
            
            // If no direct match, try fuzzy matching but with stricter rules
            $allUtterances = UtteranceGallery::all();
            $bestMatch = null;
            $highestSimilarity = 0;
            
            foreach ($allUtterances as $utterance) {
                $utteranceText = strtolower($utterance->acceptedUtterance);
                $utteranceText = $this->normalizeTranscript($utteranceText);
                
                // Calculate similarity
                $similarity = $this->calculateSimilarity($transcript, $utteranceText);
                
                // Check for significant word overlap - more strict
                $menuItemWords = explode(' ', strtolower($utterance->menuItem));
                $transcriptWords = explode(' ', $transcript);
                
                $wordMatchCount = 0;
                foreach ($menuItemWords as $word) {
                    if (strlen($word) > 2) {
                        foreach ($transcriptWords as $tWord) {
                            // Use string comparison instead of just contains
                            if (levenshtein($word, $tWord) <= 2) {
                                $wordMatchCount++;
                                break;
                            }
                        }
                    }
                }
                
                // REQUIREMENT: For a match to be considered at all, we need at least one keyword match
                if ($wordMatchCount === 0 && $similarity < 0.7) {
                    continue; // Skip this match entirely
                }
                
                // Boost similarity if we have word matches
                if ($wordMatchCount > 0) {
                    $similarity += ($wordMatchCount * 0.15); // Increased weight
                }
                
                // Penalize matches that are too short compared to the transcript
                $lengthRatio = strlen($utteranceText) / strlen($transcript);
                if ($lengthRatio < 0.3 || $lengthRatio > 3.0) {
                    $similarity *= 0.5; // Reduce similarity for very different lengths
                }
                
                if ($similarity > $highestSimilarity) {
                    $highestSimilarity = $similarity;
                    $bestMatch = $utterance;
                }
            }
            
            // Set a minimum similarity threshold for ANY match
            $minimumSimilarity = 0.5; // 50% similarity required for any match
            
            if ($bestMatch && $highestSimilarity >= $minimumSimilarity) {
                // ADJUSTED CONFIDENCE THRESHOLDS:
                // Confident ≥ 80%, Partially Confident ≥ 60%, Not Confident < 60%
                $confidence = 'Not Confident';
                if ($highestSimilarity >= 0.8) {
                    $confidence = 'Confident';
                } elseif ($highestSimilarity >= 0.6) {
                    $confidence = 'Partially Confident';
                }
                
                // Even if we have a "Not Confident" match, return it but with low confidence
                return response()->json([
                    'success' => true,
                    'matchedMenuItem' => $bestMatch->menuItem,
                    'confidence' => $confidence,
                    'similarity' => $highestSimilarity,
                    'matchType' => 'fuzzy'
                ]);
            }
            
            // If we have a match but below minimum similarity, return as not confident
            if ($bestMatch && $highestSimilarity < $minimumSimilarity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Poor match quality',
                    'matchedMenuItem' => $bestMatch->menuItem,
                    'confidence' => 'Not Confident',
                    'similarity' => $highestSimilarity,
                    'matchType' => 'poor_fuzzy'
                ]);
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
     * Get list of ambiguous terms that need clarification
     */
    private function getAmbiguousTerms()
    {
        return [
            // Main Protein Categories
            'chicken' => [
                'name' => 'Chicken',
                'category' => 'main-course',
                'subcategories' => ['chicken'],
                'prompt' => 'What type of chicken dish would you like?'
            ],
            'pork' => [
                'name' => 'Pork',
                'category' => 'main-course',
                'subcategories' => ['pork'],
                'prompt' => 'What type of pork dish would you like?'
            ],
            'beef' => [
                'name' => 'Beef',
                'category' => 'main-course',
                'subcategories' => ['beef'],
                'prompt' => 'What type of beef dish would you like?'
            ],
            'steak' => [
                'name' => 'Steak',
                'category' => 'main-course',
                'subcategories' => ['beef'],
                'prompt' => 'What type of steak would you like?'
            ],
            
            // Seafood Categories
            'seafood' => [
                'name' => 'Seafood',
                'category' => 'main-course',
                'subcategories' => ['fish-seafood'],
                'prompt' => 'What type of seafood dish would you like?'
            ],
            'fish' => [
                'name' => 'Fish',
                'category' => 'main-course',
                'subcategories' => ['fish-seafood'],
                'prompt' => 'What type of fish dish would you like?'
            ],
            'shrimp' => [
                'name' => 'Shrimp',
                'category' => 'main-course',
                'subcategories' => ['fish-seafood'],
                'prompt' => 'What type of shrimp dish would you like?'
            ],
            'crab' => [
                'name' => 'Crab',
                'category' => 'main-course',
                'subcategories' => ['fish-seafood'],
                'prompt' => 'What type of crab dish would you like?'
            ],
            
            // Pasta & Noodles
            'pasta' => [
                'name' => 'Pasta',
                'category' => 'main-course',
                'subcategories' => ['pasta'],
                'prompt' => 'What type of pasta would you like?'
            ],
            'paella' => [
                'name' => 'Paella',
                'category' => 'main-course',
                'subcategories' => ['pasta', 'fish-seafood'],
                'prompt' => 'What type of paella would you like?'
            ],
            'noodle' => [
                'name' => 'Noodles',
                'category' => 'main-course',
                'subcategories' => ['noodles'],
                'prompt' => 'What type of noodles would you like?'
            ],
            
            // Appetizers & Sides
            'salad' => [
                'name' => 'Salad',
                'category' => 'appetizers',
                'subcategories' => ['salads'],
                'prompt' => 'What type of salad would you like?'
            ],
            'sandwich' => [
                'name' => 'Sandwich',
                'category' => 'appetizers',
                'subcategories' => ['sandwiches'],
                'prompt' => 'What type of sandwich would you like?'
            ],
            'wrap' => [
                'name' => 'Wrap',
                'category' => 'appetizers',
                'subcategories' => ['sandwiches'],
                'prompt' => 'What type of wrap would you like?'
            ],
            'quesadilla' => [
                'name' => 'Quesadilla',
                'category' => 'appetizers',
                'subcategories' => ['knick-knacks'],
                'prompt' => 'What type of quesadilla would you like?'
            ],
            'fries' => [
                'name' => 'Fries',
                'category' => 'appetizers',
                'subcategories' => ['knick-knacks'],
                'prompt' => 'What type of fries would you like?'
            ],
            'wings' => [
                'name' => 'Wings',
                'category' => 'appetizers',
                'subcategories' => ['knick-knacks'],
                'prompt' => 'What type of wings would you like?'
            ],
            
            // Drink Types
            'drink' => [
                'name' => 'Drink',
                'category' => 'drinks',
                'subcategories' => ['hot', 'iced', 'frappe', 'milktea'],
                'prompt' => 'What type of drink would you like?'
            ],
            'coffee' => [
                'name' => 'Coffee',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of coffee would you like?'
            ],
            'espresso' => [
                'name' => 'Espresso',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of espresso drink would you like?'
            ],
            'americano' => [
                'name' => 'Americano',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of Americano would you like?'
            ],
            'latte' => [
                'name' => 'Latte',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of latte would you like?'
            ],
            'cappuccino' => [
                'name' => 'Cappuccino',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of cappuccino would you like?'
            ],
            'mocha' => [
                'name' => 'Mocha',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of mocha would you like?'
            ],
            'macchiato' => [
                'name' => 'Macchiato',
                'category' => 'drinks',
                'subcategories' => ['hot'],
                'prompt' => 'What type of macchiato would you like?'
            ],
            'tea' => [
                'name' => 'Tea',
                'category' => 'drinks',
                'subcategories' => ['hot', 'iced'],
                'prompt' => 'What type of tea would you like?'
            ],
            'milktea' => [
                'name' => 'Milk Tea',
                'category' => 'drinks',
                'subcategories' => ['milktea'],
                'prompt' => 'What type of milk tea would you like?'
            ],
            'frappe' => [
                'name' => 'Frappe',
                'category' => 'drinks',
                'subcategories' => ['frappe'],
                'prompt' => 'What type of frappe would you like?'
            ],
            'iced' => [
                'name' => 'Iced Drink',
                'category' => 'drinks',
                'subcategories' => ['iced'],
                'prompt' => 'What type of iced drink would you like?'
            ],
            
            // Desserts & Sweets
            'dessert' => [
                'name' => 'Dessert',
                'prompt' => 'What type of dessert would you like?'
            ],
            'cheesecake' => [
                'name' => 'Cheesecake',
                'prompt' => 'What type of cheesecake would you like?'
            ],
            'chocolate' => [
                'name' => 'Chocolate',
                'prompt' => 'What type of chocolate item would you like?'
            ],
            'caramel' => [
                'name' => 'Caramel',
                'prompt' => 'What type of caramel item would you like?'
            ],
            'vanilla' => [
                'name' => 'Vanilla',
                'prompt' => 'What type of vanilla item would you like?'
            ],
            'matcha' => [
                'name' => 'Matcha',
                'prompt' => 'What type of matcha item would you like?'
            ],
            'hazelnut' => [
                'name' => 'Hazelnut',
                'prompt' => 'What type of hazelnut item would you like?'
            ],
            'butterscotch' => [
                'name' => 'Butterscotch',
                'prompt' => 'What type of butterscotch item would you like?'
            ],
            'mango' => [
                'name' => 'Mango',
                'prompt' => 'What type of mango item would you like?'
            ],
            
            // Cooking Styles & Preparations
            'grilled' => [
                'name' => 'Grilled',
                'prompt' => 'What type of grilled item would you like?'
            ],
            'roasted' => [
                'name' => 'Roasted',
                'prompt' => 'What type of roasted item would you like?'
            ],
            'crispy' => [
                'name' => 'Crispy',
                'prompt' => 'What type of crispy item would you like?'
            ],
            'barbecue' => [
                'name' => 'Barbecue',
                'prompt' => 'What type of barbecue item would you like?'
            ],
            'barbeque' => [
                'name' => 'Barbecue',
                'prompt' => 'What type of barbecue item would you like?'
            ],
            'french' => [
                'name' => 'French',
                'prompt' => 'What type of French item would you like?'
            ],
            
            // Sauces & Toppings
            'garlic' => [
                'name' => 'Garlic',
                'prompt' => 'What type of garlic item would you like?'
            ],
            'cheesy' => [
                'name' => 'Cheesy',
                'prompt' => 'What type of cheesy item would you like?'
            ],
            'cream' => [
                'name' => 'Cream',
                'prompt' => 'What type of cream item would you like?'
            ],
            'butter' => [
                'name' => 'Butter',
                'prompt' => 'What type of butter item would you like?'
            ],
            'sauce' => [
                'name' => 'Sauce',
                'prompt' => 'What type of sauced item would you like?'
            ],
            'salted' => [
                'name' => 'Salted',
                'prompt' => 'What type of salted item would you like?'
            ],
            'white' => [
                'name' => 'White',
                'prompt' => 'What type of white item would you like?'
            ],
            'plain' => [
                'name' => 'Plain',
                'prompt' => 'What type of plain item would you like?'
            ],
            
            // Special Items
            'platter' => [
                'name' => 'Platter',
                'prompt' => 'What type of platter would you like?'
            ],
            'bagnet' => [
                'name' => 'Bagnet',
                'category' => 'main-course',
                'subcategories' => ['pork'],
                'prompt' => 'What type of bagnet dish would you like?'
            ],
            'double' => [
                'name' => 'Double',
                'prompt' => 'What type of double item would you like?'
            ],
            
            // Brand/Specific Names
            'arabica' => [
                'name' => 'Arabica',
                'prompt' => 'What type of Arabica item would you like?'
            ],
            'adora' => [
                'name' => 'Adora\'s',
                'prompt' => 'What type of Adora\'s item would you like?'
            ],
            'caffe' => [
                'name' => 'Caffé',
                'prompt' => 'What type of Caffé item would you like?'
            ]
        ];
    }

    /**
     * Search for products matching an ambiguous term
     */
    private function searchForProducts($term)
    {
        $products = [];
        
        // Get ambiguous term info
        $ambiguousTerms = $this->getAmbiguousTerms();
        
        if (!isset($ambiguousTerms[$term])) {
            return $products;
        }
        
        $termInfo = $ambiguousTerms[$term];
        
        // Search in specific categories/subcategories if defined
        if (isset($termInfo['category']) && isset($termInfo['subcategories'])) {
            foreach ($termInfo['subcategories'] as $subcategory) {
                $foundProducts = MenuProduct::where('menuStatus', 'Available')
                    ->where('menuCategory', $termInfo['category'])
                    ->where('menuSubcategory', $subcategory)
                    ->orderBy('menuName')
                    ->limit(10) // Limit to 10 products per subcategory
                    ->get(['menuName', 'menuPrice', 'menuImage', 'menuDescription']);
                
                foreach ($foundProducts as $product) {
                    $products[] = [
                        'name' => $product->menuName,
                        'price' => $product->menuPrice,
                        'image' => $product->menuImage,
                        'description' => $product->menuDescription,
                        'category' => $termInfo['category'],
                        'subcategory' => $subcategory
                    ];
                }
            }
        } else {
            // Broader search across all products
            $foundProducts = MenuProduct::where('menuStatus', 'Available')
                ->where(function($query) use ($term) {
                    $query->where('menuName', 'LIKE', "%{$term}%")
                          ->orWhere('menuDescription', 'LIKE', "%{$term}%");
                })
                ->orderBy('menuName')
                ->limit(15) // Limit total results
                ->get(['menuName', 'menuPrice', 'menuImage', 'menuDescription', 'menuCategory', 'menuSubcategory']);
            
            foreach ($foundProducts as $product) {
                $products[] = [
                    'name' => $product->menuName,
                    'price' => $product->menuPrice,
                    'image' => $product->menuImage,
                    'description' => $product->menuDescription,
                    'category' => $product->menuCategory,
                    'subcategory' => $product->menuSubcategory
                ];
            }
        }
        
        return $products;
    }
    
    /**
     * Extract order item from common ordering phrases
     */
    private function extractOrderItemFromPhrase($transcript)
    {
        // Common ordering phrases to remove
        $orderingPhrases = [
            'i want to order',
            'i would like to order',
            'i\'d like to order',
            'can i have',
            'can i get',
            'i want',
            'i\'d like',
            'give me',
            'please give me',
            'let me have',
            'i need',
            'i\'ll take',
            'i\'ll have',
            'order',
            'add'
        ];
        
        $cleaned = $transcript;
        
        // Remove ordering phrases
        foreach ($orderingPhrases as $phrase) {
            if (strpos($cleaned, $phrase) === 0) {
                $cleaned = trim(str_replace($phrase, '', $cleaned));
                break;
            }
        }
        
        // Remove quantity words
        $quantityWords = ['a', 'an', 'one', 'two', 'three', 'four', 'five', 'some'];
        $words = explode(' ', $cleaned);
        $filteredWords = array_filter($words, function($word) use ($quantityWords) {
            return !in_array($word, $quantityWords);
        });
        
        $cleaned = implode(' ', $filteredWords);
        
        // Remove punctuation
        $cleaned = preg_replace('/[^a-z0-9\s]/', '', $cleaned);
        
        // Remove filler words again (including 'and' and 'with')
        $cleaned = $this->normalizeTranscript($cleaned);
        
        return trim($cleaned);
    }

    /**
     * Normalize transcript text
     */
    private function normalizeTranscript($text)
    {
        // Remove common filler words (including 'and' and 'with' from your list)
        $fillerWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'please', 'thank you', 'thanks'];
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
        
        // If both strings are empty, return 0
        if (empty($str1) && empty($str2)) return 0;
        
        // If one is empty and the other isn't, return very low similarity
        if (empty($str1) || empty($str2)) return 0.1;
        
        // Use levenshtein distance for short strings
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $maxLen = max($len1, $len2);
        
        $distance = levenshtein($str1, $str2);
        $similarity = 1 - ($distance / $maxLen);
        
        // Use Jaro-Winkler distance for better string matching
        $jaroSimilarity = $this->jaroWinklerSimilarity($str1, $str2);
        
        // Take the better of the two similarity scores
        $finalSimilarity = max($similarity, $jaroSimilarity);
        
        return max(0, min(1, $finalSimilarity));
    }

    /**
     * Calculate Jaro-Winkler similarity
     */
    private function jaroWinklerSimilarity($str1, $str2)
    {
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        
        if ($len1 == 0 && $len2 == 0) return 0;
        
        // Calculate matching characters
        $matchDistance = (int)floor(max($len1, $len2) / 2) - 1;
        $matches = 0;
        $transpositions = 0;
        
        $str1Matches = array_fill(0, $len1, false);
        $str2Matches = array_fill(0, $len2, false);
        
        // Find matching characters
        for ($i = 0; $i < $len1; $i++) {
            $start = max(0, $i - $matchDistance);
            $end = min($i + $matchDistance + 1, $len2);
            
            for ($j = $start; $j < $end; $j++) {
                if (!$str2Matches[$j] && $str1[$i] == $str2[$j]) {
                    $str1Matches[$i] = true;
                    $str2Matches[$j] = true;
                    $matches++;
                    break;
                }
            }
        }
        
        if ($matches == 0) return 0;
        
        // Count transpositions
        $k = 0;
        for ($i = 0; $i < $len1; $i++) {
            if ($str1Matches[$i]) {
                while (!$str2Matches[$k]) $k++;
                if ($str1[$i] != $str2[$k]) $transpositions++;
                $k++;
            }
        }
        
        $transpositions /= 2;
        
        // Calculate Jaro similarity
        $jaro = (($matches / $len1) + ($matches / $len2) + (($matches - $transpositions) / $matches)) / 3;
        
        // Calculate Jaro-Winkler similarity (prefix bonus)
        $prefix = 0;
        $maxPrefix = min(4, $len1, $len2);
        for ($i = 0; $i < $maxPrefix; $i++) {
            if ($str1[$i] == $str2[$i]) {
                $prefix++;
            } else {
                break;
            }
        }
        
        $winkler = $jaro + ($prefix * 0.1 * (1 - $jaro));
        
        return $winkler;
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
                'confidenceLevel' => 'nullable|in:Not Confident,Partially Confident,Confident,Ambiguous',
            ]);
            
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
            
            // AUTO-ADD TO UTTERANCE GALLERY FOR CONFIDENT OR PARTIALLY CONFIDENT MATCHES
            if (isset($validated['matchedMenuItem']) && 
                isset($validated['confidenceLevel']) &&
                in_array($validated['confidenceLevel'], ['Confident', 'Partially Confident'])) {
                
                $this->addToUtteranceGallery(
                    $validated['matchedMenuItem'],
                    $validated['transcribedData'],
                    $validated['confidenceLevel']
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Transcript saved successfully',
                'voiceID' => $transcript->voiceID,
                'confidenceLevel' => $transcript->confidence_level,
                'addedToGallery' => isset($validated['matchedMenuItem']) && 
                                    isset($validated['confidenceLevel']) &&
                                    in_array($validated['confidenceLevel'], ['Confident', 'Partially Confident'])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving voice transcript: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save transcript: ' . $e->getMessage()
            ], 500);
        }
    }

    private function addToUtteranceGallery($menuItem, $utterance, $confidenceLevel)
    {
        try {
            // Normalize the utterance
            $normalizedUtterance = $this->normalizeUtteranceForGallery($utterance);
            
            // Check if this exact utterance already exists for this menu item
            $existing = UtteranceGallery::where('menuItem', $menuItem)
                ->where('acceptedUtterance', $normalizedUtterance)
                ->first();
            
            if (!$existing) {
                // Add new utterance to gallery
                $galleryEntry = new UtteranceGallery();
                $galleryEntry->menuItem = $menuItem;
                $galleryEntry->acceptedUtterance = $normalizedUtterance;
                $galleryEntry->save();
                
                \Log::info('Added new utterance to gallery:', [
                    'menuItem' => $menuItem,
                    'utterance' => $normalizedUtterance,
                    'confidence' => $confidenceLevel
                ]);
                
                return true;
            }
            
            return false; // Already exists
            
        } catch (\Exception $e) {
            \Log::error('Error adding to utterance gallery: ' . $e->getMessage());
            return false;
        }
    }

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
        
        return trim($normalized);
    }
}