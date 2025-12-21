# Principle Module API Documentation

## Overview

The Principle API provides public endpoints to fetch company principles/values for display on the frontend. All endpoints are **publicly accessible** (no authentication required) and return JSON responses.

**Base URL:** `http://your-domain.com/api`

---

## Endpoints

### 1. Get All Principles

Retrieves all active principles ordered by `sort_order`.

**Endpoint:** `GET /api/principles`

**Response Example:**

```json
{
    "success": true,
    "message": "Principles retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Innovation First",
            "subtitle": "Leading with creativity",
            "description": "We prioritize innovative solutions that drive industry forward...",
            "icon": "http://example.com/storage/principles/icons/innovation.svg",
            "image": "http://example.com/storage/principles/images/innovation.jpg",
            "sort_order": 1
        },
        {
            "id": 2,
            "title": "Customer Centric",
            "subtitle": "Putting clients first",
            "description": "Every decision we make is guided by our commitment to customer success...",
            "icon": "http://example.com/storage/principles/icons/customer.svg",
            "image": "http://example.com/storage/principles/images/customer.jpg",
            "sort_order": 2
        }
    ],
    "meta": {
        "total": 2,
        "timestamp": "2024-12-22T10:30:00.000000Z"
    }
}
```

**Status Codes:**

-   `200 OK` - Success
-   `500 Internal Server Error` - Server error

---

### 2. Get Single Principle

Retrieves a specific principle by ID (only if active).

**Endpoint:** `GET /api/principles/{id}`

**Parameters:**

-   `id` (integer, required) - The principle ID

**Response Example (Success):**

```json
{
    "success": true,
    "message": "Principle retrieved successfully",
    "data": {
        "id": 1,
        "title": "Innovation First",
        "subtitle": "Leading with creativity",
        "description": "We prioritize innovative solutions that drive industry forward...",
        "icon": "http://example.com/storage/principles/icons/innovation.svg",
        "image": "http://example.com/storage/principles/images/innovation.jpg",
        "sort_order": 1
    }
}
```

**Response Example (Not Found):**

```json
{
    "success": false,
    "message": "Principle not found or inactive"
}
```

**Status Codes:**

-   `200 OK` - Success
-   `404 Not Found` - Principle not found or inactive
-   `500 Internal Server Error` - Server error

---

### 3. Get Statistics

Retrieves statistical information about principles.

**Endpoint:** `GET /api/principles/stats/overview`

**Response Example:**

```json
{
    "success": true,
    "message": "Statistics retrieved successfully",
    "data": {
        "total": 10,
        "active": 8,
        "inactive": 2
    }
}
```

**Status Codes:**

-   `200 OK` - Success
-   `500 Internal Server Error` - Server error

---

## Data Model

### Principle Object

| Field         | Type         | Description                          |
| ------------- | ------------ | ------------------------------------ |
| `id`          | integer      | Unique identifier                    |
| `title`       | string       | Main title of the principle          |
| `subtitle`    | string       | Short subtitle/tagline               |
| `description` | text         | Detailed description                 |
| `icon`        | string (URL) | Full URL to the icon image (SVG/PNG) |
| `image`       | string (URL) | Full URL to the main image (JPG/PNG) |
| `sort_order`  | integer      | Display order (ascending)            |

---

## Performance & Caching

-   **Response Caching:** All endpoints use server-side caching (1 hour for list/detail, 30 minutes for stats)
-   **Optimization Tips:**
    -   Implement client-side caching to minimize API calls
    -   Use the statistics endpoint for lightweight data when full details aren't needed

---

## React Integration Examples

### 1. Basic Fetch with useState/useEffect

```jsx
import { useState, useEffect } from "react";

function PrinciplesSection() {
    const [principles, setPrinciples] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch("http://your-domain.com/api/principles")
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    setPrinciples(data.data);
                } else {
                    setError(data.message);
                }
                setLoading(false);
            })
            .catch((err) => {
                setError("Failed to fetch principles");
                setLoading(false);
            });
    }, []);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div className="principles-grid">
            {principles.map((principle) => (
                <div key={principle.id} className="principle-card">
                    <img src={principle.icon} alt={principle.title} />
                    <h3>{principle.title}</h3>
                    <h4>{principle.subtitle}</h4>
                    <p>{principle.description}</p>
                </div>
            ))}
        </div>
    );
}

export default PrinciplesSection;
```

---

### 2. Using Axios

```jsx
import { useState, useEffect } from "react";
import axios from "axios";

const API_BASE_URL = "http://your-domain.com/api";

function PrinciplesSection() {
    const [principles, setPrinciples] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        axios
            .get(`${API_BASE_URL}/principles`)
            .then((response) => {
                if (response.data.success) {
                    setPrinciples(response.data.data);
                }
                setLoading(false);
            })
            .catch((err) => {
                setError(err.message);
                setLoading(false);
            });
    }, []);

    // ... rest of component
}
```

---

### 3. Custom Hook (Reusable)

```jsx
// hooks/usePrinciples.js
import { useState, useEffect } from "react";

const API_BASE_URL = "http://your-domain.com/api";

export function usePrinciples() {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPrinciples = async () => {
            try {
                const response = await fetch(`${API_BASE_URL}/principles`);
                const result = await response.json();

                if (result.success) {
                    setData(result.data);
                } else {
                    setError(result.message);
                }
            } catch (err) {
                setError("Failed to fetch principles");
            } finally {
                setLoading(false);
            }
        };

        fetchPrinciples();
    }, []);

    return { principles: data, loading, error };
}

// Usage in component
import { usePrinciples } from "./hooks/usePrinciples";

function PrinciplesSection() {
    const { principles, loading, error } = usePrinciples();

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div className="principles-grid">
            {principles.map((principle) => (
                <PrincipleCard key={principle.id} principle={principle} />
            ))}
        </div>
    );
}
```

---

### 4. With React Query (Recommended for Production)

```jsx
// api/principles.js
import axios from "axios";

const API_BASE_URL = "http://your-domain.com/api";

export const principlesApi = {
    getAll: () => axios.get(`${API_BASE_URL}/principles`),
    getById: (id) => axios.get(`${API_BASE_URL}/principles/${id}`),
    getStats: () => axios.get(`${API_BASE_URL}/principles/stats/overview`),
};

// components/PrinciplesSection.jsx
import { useQuery } from "@tanstack/react-query";
import { principlesApi } from "../api/principles";

function PrinciplesSection() {
    const { data, isLoading, error } = useQuery({
        queryKey: ["principles"],
        queryFn: async () => {
            const response = await principlesApi.getAll();
            return response.data.data;
        },
        staleTime: 1000 * 60 * 30, // 30 minutes
    });

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error.message}</div>;

    return (
        <div className="principles-grid">
            {data?.map((principle) => (
                <PrincipleCard key={principle.id} principle={principle} />
            ))}
        </div>
    );
}
```

---

### 5. TypeScript Types (Optional)

```typescript
// types/principle.ts
export interface Principle {
    id: number;
    title: string;
    subtitle: string;
    description: string;
    icon: string;
    image: string;
    sort_order: number;
}

export interface PrinciplesResponse {
    success: boolean;
    message: string;
    data: Principle[];
    meta: {
        total: number;
        timestamp: string;
    };
}

export interface PrincipleResponse {
    success: boolean;
    message: string;
    data: Principle;
}

// Usage
import { Principle, PrinciplesResponse } from "./types/principle";

const fetchPrinciples = async (): Promise<Principle[]> => {
    const response = await fetch("http://your-domain.com/api/principles");
    const result: PrinciplesResponse = await response.json();
    return result.data;
};
```

---

## Environment Configuration

Create a `.env` file in your React project:

```env
REACT_APP_API_BASE_URL=http://localhost:8000/api
# or for production:
# REACT_APP_API_BASE_URL=https://your-production-domain.com/api
```

Then use it in your code:

```jsx
const API_BASE_URL = process.env.REACT_APP_API_BASE_URL;
```

---

## Error Handling Best Practices

```jsx
function PrinciplesSection() {
    const [principles, setPrinciples] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPrinciples = async () => {
            try {
                const response = await fetch(`${API_BASE_URL}/principles`);

                // Check if response is ok
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                // Check API success flag
                if (!result.success) {
                    throw new Error(
                        result.message || "Failed to fetch principles"
                    );
                }

                setPrinciples(result.data);
            } catch (err) {
                console.error("Error fetching principles:", err);
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchPrinciples();
    }, []);

    if (loading) {
        return (
            <div className="loading-spinner">
                <p>Loading principles...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="error-message">
                <p>⚠️ {error}</p>
                <button onClick={() => window.location.reload()}>Retry</button>
            </div>
        );
    }

    if (principles.length === 0) {
        return <div className="no-data">No principles available</div>;
    }

    return (
        <div className="principles-grid">
            {principles.map((principle) => (
                <PrincipleCard key={principle.id} principle={principle} />
            ))}
        </div>
    );
}
```

---

## CORS Configuration

If you encounter CORS errors, the backend may need to whitelist your React app's domain. Contact the backend administrator to add your frontend URL to the CORS allowed origins in `config/cors.php`.

**Common CORS Error:**

```
Access to fetch at 'http://backend.com/api/principles' from origin 'http://localhost:3000'
has been blocked by CORS policy
```

---

## Testing the API

### Using Browser

Simply visit: `http://your-domain.com/api/principles`

### Using cURL

```bash
# Get all principles
curl http://your-domain.com/api/principles

# Get single principle
curl http://your-domain.com/api/principles/1

# Get statistics
curl http://your-domain.com/api/principles/stats/overview
```

### Using Postman

1. Create a new GET request
2. Enter URL: `http://your-domain.com/api/principles`
3. Click "Send"

---

## Rate Limiting

Currently, there's no rate limiting implemented. Consider implementing client-side request throttling/debouncing for production use.

---

## Support & Questions

For backend issues or feature requests, contact the backend development team or check the other documentation files:

-   `PRINCIPLE_QUICK_START.md` - Quick start guide
-   `PRINCIPLE_CRUD_DOCUMENTATION.md` - Admin CRUD operations
-   `PRINCIPLE_ARCHITECTURE.md` - Architecture details

---

## Changelog

-   **v1.0.0** - Initial API release with GET endpoints for principles
