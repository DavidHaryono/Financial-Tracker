<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReceiptParserService
{
    /**
     * Parse receipt image and extract expense information
     * Uses multiple free AI APIs with automatic fallback
     */
    public function parseReceipt($imagePath)
    {
        try {
            // Method 1: Groq API (FREE - Claude/Llama models with vision)
            if (config('services.groq.api_key')) {
                $result = $this->parseWithGroq($imagePath);
                if ($result['success']) {
                    return $result;
                }
            }

            // Method 2: Together AI (FREE tier - multiple vision models)
            if (config('services.together.api_key')) {
                $result = $this->parseWithTogether($imagePath);
                if ($result['success']) {
                    return $result;
                }
            }

            // Method 3: Tesseract OCR + Free LLM for understanding
            $result = $this->parseWithTesseractAndLLM($imagePath);
            if ($result['success']) {
                return $result;
            }

            // Fallback: Pure Tesseract with pattern matching
            return $this->parseWithTesseract($imagePath);

        } catch (\Exception $e) {
            Log::error('Receipt parsing exception', [
                'error' => $e->getMessage(),
                'file' => $imagePath
            ]);
            return $this->getDefaultResponse();
        }
    }

    /**
     * Method 1: Groq API (Llama 3.2 Vision - FREE)
     * Get API key from: https://console.groq.com
     */
    protected function parseWithGroq($imagePath)
    {
        try {
            Log::info('Attempting Groq API parsing');

            // Read and encode image
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.api_key'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.2-90b-vision-preview',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $this->getPrompt(),
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$imageData}"
                                ],
                            ],
                        ],
                    ],
                ],
                'temperature' => 0.1,
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['choices'][0]['message']['content'] ?? '';
                
                Log::info('Groq API response', ['content' => $content]);
                
                return $this->parseAIResponse($content);
            }

            Log::error('Groq API error', ['response' => $response->body()]);
            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Groq exception', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Method 2: Together AI (FREE tier available)
     * Get API key from: https://api.together.xyz
     */
    protected function parseWithTogether($imagePath)
    {
        try {
            Log::info('Attempting Together AI parsing');

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.together.api_key'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.together.xyz/v1/chat/completions', [
                'model' => 'meta-llama/Llama-3.2-11B-Vision-Instruct-Turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $this->getPrompt(),
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$imageData}"
                                ],
                            ],
                        ],
                    ],
                ],
                'temperature' => 0.1,
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['choices'][0]['message']['content'] ?? '';
                
                Log::info('Together AI response', ['content' => $content]);
                
                return $this->parseAIResponse($content);
            }

            Log::error('Together AI error', ['response' => $response->body()]);
            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Together exception', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Method 3: Tesseract OCR + Free LLM (Best hybrid approach)
     */
    protected function parseWithTesseractAndLLM($imagePath)
    {
        try {
            Log::info('Attempting Tesseract + LLM parsing');

            // First, extract text with Tesseract
            $text = $this->extractTextWithTesseract($imagePath);
            
            if (empty($text)) {
                Log::warning('No text extracted from Tesseract');
                return ['success' => false];
            }

            Log::info('Tesseract extracted text', ['text' => substr($text, 0, 200)]);

            // Then use free LLM to understand the text
            return $this->analyzeTextWithLLM($text);

        } catch (\Exception $e) {
            Log::error('Tesseract + LLM exception', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Extract text using Tesseract
     */
    protected function extractTextWithTesseract($imagePath)
    {
        try {
            // Try different PSM modes for better accuracy
            $modes = [6, 11, 3, 4];
            
            foreach ($modes as $mode) {
                $command = sprintf(
                    'tesseract %s stdout --psm %d 2>&1',
                    escapeshellarg($imagePath),
                    $mode
                );
                
                $text = shell_exec($command);
                
                // Remove the "Estimating resolution" line
                $lines = explode("\n", $text);
                $cleanLines = array_filter($lines, function($line) {
                    return !str_contains(strtolower($line), 'estimating resolution');
                });
                $text = implode("\n", $cleanLines);
                
                // If we got meaningful text (more than 10 chars), return it
                if (strlen(trim($text)) > 10) {
                    Log::info("Tesseract PSM {$mode} extracted text", [
                        'length' => strlen($text),
                        'preview' => substr($text, 0, 100)
                    ]);
                    return trim($text);
                }
            }

            return '';

        } catch (\Exception $e) {
            Log::error('Tesseract extraction error', ['error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Analyze extracted text with free LLM
     */
    protected function analyzeTextWithLLM($text)
    {
        try {
            // Try Groq first (fastest and most reliable free option)
            if (config('services.groq.api_key')) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.groq.api_key'),
                    'Content-Type' => 'application/json',
                ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-70b-versatile',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert at analyzing receipt text and extracting structured data. Always respond with valid JSON only.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $this->getTextAnalysisPrompt($text),
                        ],
                    ],
                    'temperature' => 0.1,
                    'max_tokens' => 1024,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $content = $result['choices'][0]['message']['content'] ?? '';
                    
                    Log::info('LLM analysis response', ['content' => $content]);
                    
                    return $this->parseAIResponse($content);
                }
            }

            // Fallback to pattern matching if LLM fails
            Log::warning('LLM analysis failed, using pattern matching');
            return $this->extractDataFromText($text);

        } catch (\Exception $e) {
            Log::error('LLM analysis exception', ['error' => $e->getMessage()]);
            return $this->extractDataFromText($text);
        }
    }

    /**
     * Fallback: Pure Tesseract with pattern matching
     */
    protected function parseWithTesseract($imagePath)
    {
        try {
            Log::info('Using Tesseract pattern matching fallback');

            $text = $this->extractTextWithTesseract($imagePath);
            
            if (empty($text)) {
                Log::warning('Tesseract extracted no text');
                return $this->getDefaultResponse();
            }

            return $this->extractDataFromText($text);

        } catch (\Exception $e) {
            Log::error('Tesseract parsing error', ['error' => $e->getMessage()]);
            return $this->getDefaultResponse();
        }
    }

    /**
     * Get AI prompt for receipt analysis
     */
    protected function getPrompt()
    {
        return <<<PROMPT
    You are a receipt extraction system for expense tracking.
    Accuracy and consistency are CRITICAL.

    Your task is to extract structured data from a RECEIPT IMAGE.

    You MUST follow the rules below EXACTLY.

    ================================================
    STEP 1 — MERCHANT IDENTIFICATION (TITLE)
    ================================================
    Identify the MERCHANT or STORE NAME.

    Rules:
    - Usually appears near the TOP of the receipt.
    - Prefer LARGE or PROMINENT text.
    - DO NOT use:
    - addresses
    - phone numbers
    - cashier names
    - slogans
    - dates or receipt numbers
    - If a known brand exists (Indomaret, Alfamart, Gramedia, Miniso, IKEA, KKV, Mr DIY, Grab, Gojek, Pertamina), ALWAYS use it.

    Title format:
    "<Merchant Name> Purchase"

    If merchant is unclear:
    "Receipt Purchase"

    ================================================
    STEP 2 — FINAL AMOUNT
    ================================================
    Extract ONLY the FINAL PAID AMOUNT.

    Priority order:
    1. GRAND TOTAL
    2. TOTAL BAYAR
    3. TOTAL
    4. BAYAR / AMOUNT PAID

    Rules:
    - Ignore:
    - item prices
    - tax alone
    - discounts
    - change (kembali)
    - Choose the MOST FINAL amount.
    - INTEGER only (no symbols).

    ================================================
    STEP 3 — CATEGORY CLASSIFICATION
    ================================================
    Choose EXACTLY ONE category:

    transportation
    food
    home_utilities
    entertainment

    IMPORTANT CATEGORY RULES:

    A. TRANSPORTATION
    - Fuel, parking, toll, taxi, ride-hailing, public transport

    B. FOOD
    - Restaurant, cafe, grocery, minimarket
    - Bakery, cake shop, chocolate, snacks, hampers
    - Food purchased as GIFTS belongs to FOOD

    C. HOME_UTILITIES
    - Electricity, water, gas, internet, phone, household bills
    - Cleaning supplies, home essentials

    D. ENTERTAINMENT
    - Cinema, games, gym, subscriptions
    - Gift shops, toy stores, stationery, books, souvenirs
    - Non-food gifts (flowers, toys, accessories, decorations)

    Decision priority:
    1. Merchant type (PRIMARY)
    2. Items listed (SECONDARY)
    3. Gift intent (if present)

    ================================================
    OUTPUT
    ================================================
    Respond with VALID JSON ONLY.

    {
    "title": "Merchant Name Purchase",
    "category": "entertainment",
    "amount": 123456
    }

    NO markdown.
    NO explanation.
    PROMPT;
    }



    /**
     * Get prompt for text analysis
     */
    protected function getTextAnalysisPrompt($text)
    {
        return <<<PROMPT
    You are analyzing OCR-extracted RECEIPT TEXT.
    The text may contain errors.

    ================================================
    MERCHANT / TITLE
    ================================================
    Identify the MERCHANT NAME.

    Rules:
    - Usually in the FIRST 1–5 meaningful lines.
    - Ignore:
    - addresses (Jl., Jalan, Street, No.)
    - phone numbers
    - dates, times, receipt IDs
    - Prefer known brands if detected.

    Title format:
    "<Merchant Name> Purchase"

    If unclear:
    "Receipt Purchase"

    ================================================
    FINAL AMOUNT
    ================================================
    Extract the FINAL PAID AMOUNT.

    Keyword priority:
    GRAND TOTAL > TOTAL BAYAR > TOTAL > BAYAR > AMOUNT

    Rules:
    - Ignore:
    - item prices
    - tax
    - discount
    - change (kembali)
    - Choose the LAST or MOST FINAL amount.
    - INTEGER only.

    ================================================
    CATEGORY SELECTION
    ================================================
    Choose EXACTLY ONE:
    transportation | food | home_utilities | entertainment

    Rules:
    - Merchant name is PRIMARY signal.
    - Detect GIFT intent using words like:
    gift, hadiah, kado, souvenir, bunga, boneka, toys, stationery
    - Food gifts → food
    - Non-food gifts → entertainment

    If uncertain, choose the MOST LOGICAL consumer category.

    ================================================
    RECEIPT TEXT
    ================================================
    {$text}

    ================================================
    OUTPUT
    ================================================
    Return ONLY valid JSON:

    {
    "title": "Merchant Name Purchase",
    "category": "entertainment",
    "amount": 123456
    }
    PROMPT;
    }



    /**
     * Parse AI response (JSON)
     */
    protected function parseAIResponse($content)
    {
        try {
            // Remove markdown code blocks if present
            $content = preg_replace('/```json\s*|\s*```/', '', $content);
            $content = trim($content);
            
            // Try to find JSON in the response
            if (preg_match('/\{[^}]+\}/', $content, $matches)) {
                $content = $matches[0];
            }
            
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON parsing error', [
                    'content' => $content,
                    'error' => json_last_error_msg()
                ]);
                return ['success' => false];
            }

            $result = [
                'success' => true,
                'title' => $this->sanitizeTitle($data['title'] ?? ''),
                'category' => $this->validateCategory($data['category'] ?? ''),
                'amount' => $this->sanitizeAmount($data['amount'] ?? 0),
            ];

            Log::info('Successfully parsed AI response', $result);

            return $result;

        } catch (\Exception $e) {
            Log::error('AI response parsing exception', [
                'error' => $e->getMessage(),
                'content' => $content
            ]);
            return ['success' => false];
        }
    }

    /**
     * Extract data from text using pattern matching
     */
    protected function extractDataFromText($text)
    {
        Log::info('Extracting data using pattern matching', [
            'text_length' => strlen($text),
            'text_preview' => substr($text, 0, 200)
        ]);

        $text_lower = strtolower($text);
        
        // Extract amount
        $amount = $this->extractAmount($text_lower);
        
        // Extract title
        $lines = array_filter(explode("\n", $text), 'trim');
        $title = $this->extractTitle($lines);
        
        // Detect category
        $category = $this->detectCategory($text_lower);

        $result = [
            'success' => true,
            'title' => $title,
            'category' => $category,
            'amount' => $amount,
        ];

        Log::info('Pattern matching result', $result);

        return $result;
    }

    /**
     * Extract amount from text
     */
    protected function extractAmount($text)
    {
        $lines = explode("\n", strtolower($text));
        $candidates = [];

        foreach ($lines as $line) {
            if (preg_match('/(grand\s*total|total\s*bayar|total|bayar)/', $line)) {
                if (preg_match('/([0-9][0-9,.]+)/', $line, $m)) {
                    $value = intval(preg_replace('/[^0-9]/', '', $m[1]));
                    if ($value > 0) {
                        $candidates[] = $value;
                    }
                }
            }
        }

        if (!empty($candidates)) {
            return max($candidates);
        }

        return 0;
    }


    /**
     * Extract title from receipt lines
     */
    protected function extractTitle($lines)
    {
        foreach ($lines as $line) {
            $line = trim($line);

            if (strlen($line) < 3) continue;
            if (preg_match('/^[0-9\-\/\:\.\,\s]+$/', $line)) continue;

            // Skip addresses & phone numbers
            if (preg_match('/(jl\.|jalan|street|no\.|telp|phone)/i', $line)) continue;

            // Skip dates
            if (preg_match('/\d{2}[\/\-]\d{2}[\/\-]\d{2,4}/', $line)) continue;

            return ucwords(strtolower(substr($line, 0, 100)));
        }

        return 'Receipt Purchase';
    }


    /**
     * Detect category based on keywords
     */
    protected function detectCategory($text)
    {
        $text = strtolower($text);

        $categories = [
            'transportation' => [
                'taxi','grab','gojek','uber','fuel','bensin','pertamina','shell',
                'parking','parkir','toll','tol','bus','kereta','train','motor','ojek','pesawat',
                'angkot','transjakarta','commuter','mrt','lrt','truk',
                'spbu','gas station','stasiun','terminal','pelabuhan','port',
                'damri','angkasa','airport','bandara','helipad','travel',
                'rental','sewa mobil','car rental','bike rental','scooter',
                'carwash','cuci mobil','mobil','automotive','bengkel','workshop',
                'tire','ban','oil change','ganti oli','maintenance','perawatan',
                'driver','supir','ojol','online taxi','angkutan','public transport',
                'kendaraan','vehicle','ford','toyota','honda','suzuki','yamaha'
            ],

            'food' => [
                'restaurant','resto','cafe','kopi','coffee','makan','food',
                'indomaret','alfamart','supermarket','hypermart','grocery',
                'bakery','cake','roti','chocolate','coklat','snack',
                'gofood','grabfood','delivery','pizza','kfc','mcdonald',
                'hampers','parcel','warung','warteg','rumah makan','kedai','bistro','diner',
                'takeaway','take out','drive thru','buffet','catering',
                'minuman','drink','tea','teh','boba','bubble tea','juice','jus',
                'soda','soft drink','water','aqua','le minerale','vit',
                'martabak','burger','steak','sushi','ramen','pasta','noodles',
                'mie','bakmi','sate','satay','seafood','ikan','ayam','chicken',
                'beef','daging','vegetarian','vegan','healthy food','salad',
                'dessert','ice cream','es krim','gelato','donut','doughnut',
                'mart','alfamidi','circle k','yomart','super indo','giant',
                'carrefour','lotus','lotte mart','rancak','segari','pasar',
                'market','wet market','toko kelontong','mini market',
                'foodpanda','shopeefood','traveloka eats','dana food',
                'zipping','hangry','pahala','burger king','jco','starbucks',
                'kopi kenangan','fore','janji jiwa','chatime','koki'
            ],

            'home_utilities' => [
                'pln','listrik','electricity','pdam','air','water',
                'wifi','internet','indihome','telkom',
                'gas','lpg','pulsa','token','household','detergent','sabun',
                'briket','kerosin','kayu bakar','biosolar','solar',
                'first media','biznet','myrepublic','oxygen','mega',
                'xl home','bolt','smartfren','cable','tv kabel',
                'tv subscription','tv langganan','streaming','youtube premium',
                'disney+','hbo','viu','vision+','mola tv',
                'maintenance fee','iuran','sumbangan','contribution',
                'security','satpam','keamanan','cleaning service','kebersihan',
                'laundry','dry clean','setrika','ironing','jemput laundry',
                'repair','perbaikan','service ac','ac service','water heater',
                'dispenser','filter air','water filter','pompa air','water pump',
                'pest control','pembasmi','fumigasi','exterminator',
                'gardener','tukang kebun','pool maintenance','kolam renang',
                'asuransi rumah','home insurance','kredit rumah','home loan',
                'property tax','pbb','imb','permit','perizinan'
            ],

            'entertainment' => [
                'cinema','movie','film','bioskop','xxi','cgv',
                'game','playstation','steam',
                'spotify','netflix','subscription',
                'gym','fitness','sport',
                'gift','hadiah','kado','souvenir',
                'flower','bunga','toy','toys','boneka',
                'book','buku','stationery','alat tulis',
                'miniso','mr diy','gramedia','ikea','kkv',
                'briket','kerosin','kayu bakar','biosolar','solar',
                'hbo go','disney hotstar','prime video','apple tv','paramount',
                'youtube music','apple music','deezer','tidal','joox',
                'concert','konser','festival','pameran','exhibition',
                'museum','galeri','art gallery','teater','theater','opera',
                'karaoke','noraebang','bowling','billiard','pool',
                'arcade','game center','timezone','funworld',
                'theme park','taman bermain','dunia fantasi','dufan',
                'trans studio','waterbom','water park','sea world',
                'zoo','kebun binatang','aquarium','safari','taman safari',
                'hiking','camping','outbond','adventure','paintball',
                'laser tag','escape room','virtual reality','vr',
                'photobooth','photo studio','studio foto','prewedding',
                'craft','kerajinan','hobby','hobi','collection','koleksi',
                'musical instrument','alat musik','gitar','piano','drum',
                'art supplies','perlengkapan seni','craft supplies',
                'board game','card game','puzzle','lego','action figure',
                'antique','antik','vintage','collectible','komik','comic',
                'manga','anime','merchandise','fan merchandise',
                'tiket.com','traveloka','pegipegi','tiket event',
                'membership','keanggotaan','club','klub','community'
            ],
        ];

        $scores = array_fill_keys(array_keys($categories), 0);

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $word) {
                if (strpos($text, $word) !== false) {
                    $scores[$category] += 2;
                }
            }
        }

        // Gift override logic
        if (preg_match('/gift|hadiah|kado|souvenir|bunga|boneka|toy/', $text)) {
            if (!preg_match('/food|cake|coklat|snack|hampers/', $text)) {
                $scores['entertainment'] += 5;
            }
        }

        arsort($scores);

        $top = array_key_first($scores);
        return $scores[$top] > 0 ? $top : 'food';
    }



    /**
     * Sanitize and validate extracted data
     */
    protected function sanitizeTitle($title)
    {
        $title = strip_tags($title);
        $title = substr($title, 0, 255);
        return $title ?: 'Expense from Receipt';
    }

    protected function validateCategory($category)
    {
        $valid = ['transportation', 'food', 'home_utilities', 'entertainment'];
        return in_array($category, $valid) ? $category : 'food';
    }

    protected function sanitizeAmount($amount)
    {
        $amount = preg_replace('/[^0-9.]/', '', (string)$amount);
        $amount = floatval($amount);
        return max(0, intval($amount));
    }

    /**
     * Get default response when parsing fails
     */
    protected function getDefaultResponse()
    {
        return [
            'success' => false,
            'title' => '',
            'category' => '',
            'amount' => 0,
            'error' => 'Could not parse receipt. Please fill in the details manually.',
        ];
    }
}