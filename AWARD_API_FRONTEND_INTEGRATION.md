# Award API - Frontend Integration Guide

Complete guide for integrating the Award Module API into your React/TypeScript project.

---

## 📋 Table of Contents

-   [API Overview](#api-overview)
-   [Quick Start](#quick-start)
-   [API Endpoints](#api-endpoints)
-   [TypeScript Types](#typescript-types)
-   [React Integration](#react-integration)
-   [Error Handling](#error-handling)
-   [Environment Setup](#environment-setup)
-   [Best Practices](#best-practices)
-   [Troubleshooting](#troubleshooting)

---

## 🌐 API Overview

The Award API provides public RESTful endpoints to fetch company awards for frontend display.

**Key Features:**

-   ✅ **Public Access** - No authentication required
-   ✅ **Server-Side Caching** - 1-hour cache (60 minutes)
-   ✅ **Auto-Sorted** - Ordered by `sort_order` ascending
-   ✅ **Filtered** - Only returns active awards
-   ✅ **Consistent Format** - Standard JSON responses

**Base URL:**

```
Development: http://localhost:8000/api
Production:  https://yourdomain.com/api
```

---

## 🚀 Quick Start

### Test in Browser

Open your browser and navigate to:

```
http://localhost:8000/api/awards
```

You should see:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Solid Fundamental Crafter Async",
            "location": "Bali, 2020",
            "featured": false
        }
    ]
}
```

### Quick React Example

```tsx
import { useEffect, useState } from "react";

function Awards() {
    const [awards, setAwards] = useState([]);

    useEffect(() => {
        fetch("http://localhost:8000/api/awards")
            .then((res) => res.json())
            .then(({ data }) => setAwards(data));
    }, []);

    return (
        <div>
            {awards.map((award) => (
                <div key={award.id}>{award.title}</div>
            ))}
        </div>
    );
}
```

---

## 📡 API Endpoints

### 1️⃣ Get All Active Awards

**Endpoint:**

```http
GET /api/awards
```

**Description:**  
Returns all active awards ordered by `sort_order` (ascending).

**Response Example:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Solid Fundamental Crafter Async",
            "location": "Bali, 2020",
            "featured": false
        },
        {
            "id": 2,
            "title": "Most Crowded Yet Harmony Place",
            "location": "Shanghai, 2021",
            "featured": false
        },
        {
            "id": 3,
            "title": "Small Things Made Much Big Impacts",
            "location": "Zurich, 2022",
            "featured": true
        }
    ]
}
```

**Response Fields:**

| Field             | Type      | Description                            |
| ----------------- | --------- | -------------------------------------- |
| `success`         | `boolean` | Always `true` on successful request    |
| `data`            | `array`   | Array of award objects                 |
| `data[].id`       | `number`  | Unique award identifier                |
| `data[].title`    | `string`  | Award title                            |
| `data[].location` | `string`  | Location and year (e.g., "Bali, 2020") |
| `data[].featured` | `boolean` | Whether award is featured              |

**Characteristics:**

-   ✅ Returns only awards where `is_active = true`
-   ✅ Sorted by `sort_order` ASC, then `created_at` DESC
-   ✅ Cached for 1 hour on server
-   ✅ No pagination (all active awards returned)

---

### 2️⃣ Get Featured Awards Only

**Endpoint:**

```http
GET /api/awards/featured
```

**Description:**  
Returns only featured awards (where `featured = true` AND `is_active = true`).

**Response Example:**

```json
{
    "success": true,
    "data": [
        {
            "id": 3,
            "title": "Small Things Made Much Big Impacts",
            "location": "Zurich, 2022",
            "featured": true
        }
    ]
}
```

**Use Case:**  
Display highlighted/featured awards separately (e.g., homepage hero section).

---

### ❌ Error Response

All endpoints return consistent error responses on failure:

```json
{
    "success": false,
    "message": "Failed to fetch awards",
    "error": "Detailed error message (only in debug mode)"
}
```

**HTTP Status Codes:**

-   `200` - Success
-   `500` - Server error

---

## 📦 TypeScript Types

Create a types file for type safety:

```typescript
// types/award.ts

export interface Award {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}

export interface AwardsApiSuccessResponse {
    success: true;
    data: Award[];
}

export interface AwardsApiErrorResponse {
    success: false;
    message: string;
    error?: string;
}

export type AwardsApiResponse =
    | AwardsApiSuccessResponse
    | AwardsApiErrorResponse;
```

---

## ⚛️ React Integration

### Example 1: Basic useState + useEffect

```tsx
import { useEffect, useState } from "react";

interface Award {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}

export function AwardsList() {
    const [awards, setAwards] = useState<Award[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch("http://localhost:8000/api/awards")
            .then((res) => res.json())
            .then(({ success, data }) => {
                if (success) {
                    setAwards(data);
                }
            })
            .finally(() => setLoading(false));
    }, []);

    if (loading) return <p>Loading awards...</p>;

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {awards.map((award) => (
                <div key={award.id} className="p-6 border rounded-lg">
                    <h3 className="font-bold text-lg">{award.title}</h3>
                    <p className="text-gray-600">{award.location}</p>
                    {award.featured && (
                        <span className="text-yellow-500">⭐ Featured</span>
                    )}
                </div>
            ))}
        </div>
    );
}
```

---

### Example 2: Custom Hook (Reusable)

```typescript
// hooks/useAwards.ts
import { useState, useEffect } from "react";

interface Award {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}

interface UseAwardsOptions {
    featuredOnly?: boolean;
}

export function useAwards(options: UseAwardsOptions = {}) {
    const [awards, setAwards] = useState<Award[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const endpoint = options.featuredOnly
            ? "/api/awards/featured"
            : "/api/awards";

        fetch(`http://localhost:8000${endpoint}`)
            .then((res) => {
                if (!res.ok) throw new Error("Network response was not ok");
                return res.json();
            })
            .then(({ success, data, message }) => {
                if (success) {
                    setAwards(data);
                } else {
                    throw new Error(message || "Failed to fetch awards");
                }
            })
            .catch((err) => {
                setError(err.message);
                console.error("Error fetching awards:", err);
            })
            .finally(() => setLoading(false));
    }, [options.featuredOnly]);

    return { awards, loading, error };
}
```

**Usage:**

```tsx
import { useAwards } from "./hooks/useAwards";

function MyComponent() {
    const { awards, loading, error } = useAwards();

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error}</p>;

    return (
        <div>
            {awards.map((award) => (
                <div key={award.id}>{award.title}</div>
            ))}
        </div>
    );
}

// Get featured only
function FeaturedAwards() {
    const { awards, loading, error } = useAwards({ featuredOnly: true });
    // ...
}
```

---

### Example 3: Integration with Your Original Component

Replace static data in your `award.tsx` with API data:

```tsx
import { Award } from "lucide-react";
import { motion } from "framer-motion";
import { useEffect, useState } from "react";

interface AwardData {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}

export function Awards() {
    const [awards, setAwards] = useState<AwardData[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch("http://localhost:8000/api/awards")
            .then((res) => res.json())
            .then(({ success, data }) => {
                if (success) setAwards(data);
            })
            .catch((err) => console.error("Failed to load awards:", err))
            .finally(() => setLoading(false));
    }, []);

    if (loading) {
        return (
            <section className="py-16 lg:py-20">
                <div className="container">
                    <div className="flex items-center justify-center">
                        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
                    </div>
                </div>
            </section>
        );
    }

    return (
        <section className="py-16 lg:py-20">
            <div className="container">
                <motion.div
                    className="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8"
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: false, amount: 0.5 }}
                    transition={{ duration: 0.6 }}
                >
                    <div>
                        <span className="inline-block px-4 py-2 bg-brand-accent-light text-brand-accent text-sm font-bold rounded-full">
                            OUR AWARDS
                        </span>
                        <h2 className="mt-4 text-3xl lg:text-4xl font-bold text-foreground leading-tight">
                            We've Dedicated Our
                            <br />
                            Best Team Efforts
                        </h2>
                    </div>
                    <a
                        href="#explore"
                        className="inline-flex items-center px-5 py-3 bg-secondary text-secondary-foreground text-sm font-bold rounded-xl transition-opacity hover:opacity-90 self-start md:self-auto"
                    >
                        Explore More
                    </a>
                </motion.div>

                <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {awards.map((award, index) => (
                        <motion.article
                            key={award.id}
                            className="p-6 rounded-xl border border-border hover:border-primary transition-colors duration-300 cursor-pointer"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            viewport={{ once: false, amount: 0.3 }}
                            transition={{ duration: 0.5, delay: index * 0.1 }}
                            whileHover={{
                                y: -8,
                                transition: { duration: 0.3 },
                            }}
                            whileTap={{ y: -8, scale: 0.98 }}
                        >
                            <div className="w-12 h-12 bg-brand-accent-light rounded-lg flex items-center justify-center mb-6">
                                <Award
                                    className="text-brand-accent"
                                    size={24}
                                />
                            </div>
                            <div className="h-px w-full mb-6 bg-border" />
                            <h3 className="text-foreground text-lg font-bold">
                                {award.title}
                            </h3>
                            <div className="h-px w-full my-6 bg-border" />
                            <p className="text-muted-foreground">
                                {award.location}
                            </p>
                        </motion.article>
                    ))}
                </div>
            </div>
        </section>
    );
}
```

---

### Example 4: Using Axios

```typescript
import axios from "axios";
import { useEffect, useState } from "react";

const api = axios.create({
    baseURL: "http://localhost:8000/api",
    timeout: 10000,
    headers: {
        "Content-Type": "application/json",
    },
});

function AwardsWithAxios() {
    const [awards, setAwards] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api.get("/awards")
            .then((response) => {
                if (response.data.success) {
                    setAwards(response.data.data);
                }
            })
            .catch((error) => {
                console.error("Error fetching awards:", error);
            })
            .finally(() => {
                setLoading(false);
            });
    }, []);

    if (loading) return <div>Loading...</div>;

    return (
        <div>
            {awards.map((award) => (
                <div key={award.id}>{award.title}</div>
            ))}
        </div>
    );
}
```

---

### Example 5: React Query (Recommended for Production)

```typescript
import { useQuery } from "@tanstack/react-query";

async function fetchAwards() {
    const response = await fetch("http://localhost:8000/api/awards");
    if (!response.ok) throw new Error("Failed to fetch");
    const { success, data } = await response.json();
    if (!success) throw new Error("API returned unsuccessful response");
    return data;
}

function AwardsWithReactQuery() {
    const {
        data: awards,
        isLoading,
        error,
    } = useQuery({
        queryKey: ["awards"],
        queryFn: fetchAwards,
        staleTime: 5 * 60 * 1000, // 5 minutes
        cacheTime: 10 * 60 * 1000, // 10 minutes
    });

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error.message}</div>;

    return (
        <div>
            {awards?.map((award) => (
                <div key={award.id}>{award.title}</div>
            ))}
        </div>
    );
}
```

---

## 🛡️ Error Handling

### Complete Error Handling Example

```tsx
import { useState, useEffect } from "react";

interface Award {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}

export function AwardsWithErrorHandling() {
    const [awards, setAwards] = useState<Award[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchAwards = async () => {
            try {
                const response = await fetch(
                    "http://localhost:8000/api/awards"
                );

                // Check HTTP status
                if (!response.ok) {
                    throw new Error(
                        `HTTP ${response.status}: ${response.statusText}`
                    );
                }

                const result = await response.json();

                // Check API success flag
                if (!result.success) {
                    throw new Error(result.message || "Failed to fetch awards");
                }

                setAwards(result.data);
                setError(null);
            } catch (err) {
                const message =
                    err instanceof Error
                        ? err.message
                        : "Unknown error occurred";
                setError(message);
                console.error("Error fetching awards:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchAwards();
    }, []);

    // Loading state
    if (loading) {
        return (
            <div className="flex items-center justify-center p-8">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
        );
    }

    // Error state
    if (error) {
        return (
            <div className="p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 className="text-red-800 font-semibold mb-2">
                    Failed to load awards
                </h3>
                <p className="text-red-600 text-sm mb-3">{error}</p>
                <button
                    onClick={() => window.location.reload()}
                    className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                    Retry
                </button>
            </div>
        );
    }

    // Empty state
    if (awards.length === 0) {
        return (
            <div className="p-8 text-center text-gray-500">
                <p>No awards available at the moment.</p>
            </div>
        );
    }

    // Success state
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {awards.map((award) => (
                <div key={award.id} className="p-6 border rounded-lg">
                    <h3 className="font-bold text-lg">{award.title}</h3>
                    <p className="text-gray-600">{award.location}</p>
                </div>
            ))}
        </div>
    );
}
```

---

## ⚙️ Environment Setup

### Step 1: Create Environment File

Create `.env.local` (Vite) or `.env` (Next.js):

```env
# Vite
VITE_API_URL=http://localhost:8000

# Next.js
NEXT_PUBLIC_API_URL=http://localhost:8000
```

### Step 2: Create API Config

```typescript
// config/api.ts

const API_URL =
    import.meta.env.VITE_API_URL || // Vite
    process.env.NEXT_PUBLIC_API_URL || // Next.js
    "http://localhost:8000";

export const API_CONFIG = {
    baseURL: API_URL,
    endpoints: {
        awards: {
            all: `${API_URL}/api/awards`,
            featured: `${API_URL}/api/awards/featured`,
        },
    },
    timeout: 10000,
};
```

### Step 3: Create API Service

```typescript
// services/awardService.ts
import { API_CONFIG } from "../config/api";

export const awardService = {
    async getAll() {
        const response = await fetch(API_CONFIG.endpoints.awards.all);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const { success, data } = await response.json();
        if (!success) {
            throw new Error("Failed to fetch awards");
        }
        return data;
    },

    async getFeatured() {
        const response = await fetch(API_CONFIG.endpoints.awards.featured);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const { success, data } = await response.json();
        if (!success) {
            throw new Error("Failed to fetch featured awards");
        }
        return data;
    },
};
```

### Step 4: Use in Components

```tsx
import { useEffect, useState } from "react";
import { awardService } from "./services/awardService";

function Awards() {
    const [awards, setAwards] = useState([]);

    useEffect(() => {
        awardService
            .getAll()
            .then((data) => setAwards(data))
            .catch((err) => console.error(err));
    }, []);

    return <div>{/* ... */}</div>;
}
```

---

## 🎯 Best Practices

### 1. Use AbortController for Cleanup

```typescript
useEffect(() => {
    const controller = new AbortController();

    fetch("http://localhost:8000/api/awards", {
        signal: controller.signal,
    })
        .then((res) => res.json())
        .then(({ data }) => setAwards(data))
        .catch((err) => {
            if (err.name !== "AbortError") {
                console.error("Fetch error:", err);
            }
        });

    // Cleanup function
    return () => controller.abort();
}, []);
```

### 2. Add Request Timeout

```typescript
async function fetchWithTimeout(url: string, timeout = 10000) {
    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), timeout);

    try {
        const response = await fetch(url, { signal: controller.signal });
        clearTimeout(id);
        return response;
    } catch (error) {
        clearTimeout(id);
        throw error;
    }
}
```

### 3. Implement Retry Logic

```typescript
async function fetchWithRetry(url: string, retries = 3) {
    for (let i = 0; i < retries; i++) {
        try {
            const response = await fetch(url);
            if (response.ok) return response;
        } catch (error) {
            if (i === retries - 1) throw error;
            await new Promise((resolve) => setTimeout(resolve, 1000 * (i + 1)));
        }
    }
}
```

### 4. Loading Skeleton

```tsx
function AwardsSkeleton() {
    return (
        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {[1, 2, 3, 4].map((i) => (
                <div key={i} className="p-6 border rounded-xl animate-pulse">
                    <div className="w-12 h-12 bg-gray-200 rounded-lg mb-6"></div>
                    <div className="h-px w-full mb-6 bg-gray-200"></div>
                    <div className="h-6 bg-gray-200 rounded mb-6"></div>
                    <div className="h-px w-full my-6 bg-gray-200"></div>
                    <div className="h-4 bg-gray-200 rounded"></div>
                </div>
            ))}
        </div>
    );
}
```

---

## 🔧 Troubleshooting

### Issue 1: CORS Error

**Error:**

```
Access to fetch at 'http://localhost:8000/api/awards' from origin 'http://localhost:3000'
has been blocked by CORS policy
```

**Solution:**

Update `config/cors.php` in Laravel:

```php
return [
    'paths' => ['api/*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:5173', // Vite
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],

    'allowed_headers' => ['*'],

    'supports_credentials' => false,
];
```

Then clear config:

```bash
php artisan config:clear
```

---

### Issue 2: Network Error / Failed to Fetch

**Possible Causes:**

1. Backend server not running
2. Wrong API URL
3. Network connectivity

**Solution:**

```typescript
// Check if server is running
fetch("http://localhost:8000/api/awards")
    .then((res) => console.log("Server OK:", res.status))
    .catch((err) => console.error("Server error:", err.message));

// Verify environment variable
console.log("API URL:", import.meta.env.VITE_API_URL);
```

---

### Issue 3: Empty Data Array

**Check:**

1. Are there awards in database?
2. Are awards active (`is_active = true`)?

**Debug:**

```bash
# Check database
php artisan tinker
>>> Award::count();
>>> Award::active()->count();

# Seed if empty
php artisan db:seed --class=AwardSeeder
```

---

### Issue 4: Stale Data After Update

**Cause:** Server-side cache (1 hour)

**Solution:**

```bash
# Clear cache manually
php artisan cache:clear

# Or in Tinker
>>> Cache::forget('awards.active');
>>> Cache::forget('awards.featured');
```

---

## 📚 Additional Resources

-   [React Documentation](https://react.dev)
-   [TypeScript Handbook](https://www.typescriptlang.org/docs/)
-   [Fetch API MDN](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API)
-   [React Query](https://tanstack.com/query/latest)

---

## 🎉 Ready to Integrate!

You now have everything you need to integrate the Award API into your React project. Start with the basic example and gradually add features like:

-   ✅ Error handling
-   ✅ Loading states
-   ✅ Retry logic
-   ✅ Caching (React Query)
-   ✅ TypeScript types

Happy coding! 🚀
