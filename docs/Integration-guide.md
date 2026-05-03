# GecnoGuru Payment Microservice: Integration Guide

This guide explains how to connect your website to the centralized Payment Service. It is designed for both human developers and AI coding agents.

---

## ⚡ Quick Start: Adding a New App (5 Minutes)

**Step 1 — Register in Admin Panel**
Go to `https://payments.gecnoguru.com/admin/applications` → Click **Register New App**
- **App Name**: e.g. `GecnoGuru Subscriptions`
- **App ID**: e.g. `gecno_subscriptions` (lowercase, no spaces)
- **Base URL**: e.g. `https://subscriptions.gecnoguru.com` ← required for URL security check

Copy the generated **API Key** and **API Secret** (click the eye icon to reveal).

**Step 2 — Add to your app's `.env`**
```env
PAYMENT_GATEWAY_URL=https://payments.gecnoguru.com/api/v1
PAYMENT_APP_ID=gecno_subscriptions
PAYMENT_APP_SECRET=gec_secret_xxxxxxxxxxxxxxxx
```

**Step 3 — Call the API when user pays**
```php
$response = Http::withHeaders([
    'X-App-ID'      => env('PAYMENT_APP_ID'),
    'Authorization' => 'Bearer ' . env('PAYMENT_APP_SECRET'),
])->post(env('PAYMENT_GATEWAY_URL') . '/payments/initiate', [
    'amount'                  => 499,
    'external_transaction_id' => 'ORDER_' . $orderId,
    'customer_id'             => (string) $user->id,
    'mobile_number'           => $user->phone ?? null,
    'callback_url'            => 'https://subscriptions.gecnoguru.com/api/payment/callback',
    'redirect_url'            => 'https://subscriptions.gecnoguru.com/payment/status',
    'metadata'                => ['plan' => 'Pro'],
]);

return redirect($response['checkout_url']);
```

**Step 4 — Handle the callback on your server**
```php
// POST https://subscriptions.gecnoguru.com/api/payment/callback
public function handleCallback(Request $request)
{
    if ($request->input('status') === 'COMPLETED') {
        // Mark order as paid, unlock feature
    }
}
```

**Done! ✅** The user pays via the Payment Service and your app gets notified automatically.

---

## 1. How it works (Simple English)

Imagine the Payment Service as a **central terminal** for all GecnoGuru apps.

1.  **Preparation**: You register your website in the Payment Service Admin Panel. You get an **App ID** and an **API Secret**.
2.  **Payment Request**: When a user clicks "Pay", your website sends a secret message (API request) to the terminal saying: *"Hey, charge this user ₹500 for Order #101"*.
3.  **The Checkout**: The terminal gives you a special **Checkout Link**. You send your user to that link.
4.  **Completion**: The user pays securely. The terminal then sends the user back to your website and sends a background message to your server saying: *"Payment for Order #101 was Successful!"*.

---

## 2. Technical API Details

### Authentication
Every request must include these two headers:
- `X-App-ID`: Your registered Application ID (e.g., `career_portal`).
- `Authorization`: `Bearer YOUR_API_SECRET`.

### Base API URL
`http://payment-service-domain.com/api/v1`

---

## 3. Step-by-Step Integration Guide (For AI Agents)

If you are an AI Agent tasksed with integrating this into a website, follow these steps:

### Step 1: Initialize Payment
Send a POST request from the website's backend to the Payment Microservice.

**Endpoint:** `POST /payments/initiate`

**JSON Payload:**
```json
{
    "amount": 500,
    "external_transaction_id": "UNIQUE_ORDER_ID_FROM_YOUR_SITE",
    "customer_id": "USER_123",
    "mobile_number": "9999999999",
    "callback_url": "https://your-site.com/api/payments/webhook",
    "redirect_url": "https://your-site.com/payments/status-page",
    "metadata": {
        "item": "Premium Course",
        "coupon": "SAVE10"
    }
}
```

**What to do with the response:**
You will receive a `checkout_url`. Redirect the user's browser to this URL immediately.

---

### Step 2: Handle the Browser Redirect
After payment, the user is sent back to your `redirect_url` with parameters attached.

**URL received by your site:**
`https://your-site.com/payments/status-page?status=COMPLETED&transaction_id=UNIQUE_ORDER_ID_FROM_YOUR_SITE`

**Agent Logic:**
Verify the `status` parameter. If it is `COMPLETED`, show a "Thank You" message. If `FAILED`, show an error and ask them to try again.

---

### Step 3: Handle the Server Webhook (Callback)
The Payment Service will send a POST request to your `callback_url` to confirm the payment permanently.

**Data sent to your site:**
```json
{
    "transaction_id": "UNIQUE_ORDER_ID_FROM_YOUR_SITE",
    "gec_txn_id": "GEC_PAY_123456",
    "status": "COMPLETED",
    "amount": 500
}
```

**Agent Logic:**
1.  Check if the order is already marked as paid.
2.  Update your database to mark the order as `Paid`.
3.  Unlock the service or product for the user.

---

## 4. Troubleshooting for Agents
- **401 Unauthorized**: Check if the `X-App-ID` matches the one in the Admin Panel and if the `Bearer` token is exactly the `api_secret`.
- **422 Validation Error**: Ensure `amount` is a number and `callback_url` is a valid URL.
- **Table Names**: If querying the Payment Service database directly, use `phonepe_transactions` as the table name.

---

*This guide was generated to ensure seamless multi-tenant payment processing across the GecnoGuru ecosystem.*
