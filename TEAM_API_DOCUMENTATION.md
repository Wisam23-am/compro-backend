# Team Module API Documentation

## Overview

The Team API provides public endpoints to fetch team member information for display on the frontend. All endpoints are **publicly accessible** (no authentication required) and return JSON responses.

**Base URL:** `http://your-domain.com/api`

---

## Endpoints

### 1. Get All Team Members

Retrieves all active team members ordered by `sort_order`.

**Endpoint:** `GET /api/team`

**Response Example:**

```json
{
    "success": true,
    "message": "Team members retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "position": "CEO & Founder",
            "location": "San Francisco, CA",
            "image": "http://example.com/storage/team/john-doe.jpg",
            "sort_order": 1
        },
        {
            "id": 2,
            "name": "Jane Smith",
            "position": "Chief Technology Officer",
            "location": "New York, NY",
            "image": "http://example.com/storage/team/jane-smith.jpg",
            "sort_order": 2
        }
    ],
    "count": 2
}
```

**Status Codes:**

-   `200 OK` - Success
-   `500 Internal Server Error` - Server error

---

### 2. Get Single Team Member

Retrieves a specific team member by ID (only if active).

**Endpoint:** `GET /api/team/{id}`

**Parameters:**

-   `id` (integer, required) - The team member ID

**Response Example (Success):**

```json
{
    "success": true,
    "message": "Team member retrieved successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "position": "CEO & Founder",
        "location": "San Francisco, CA",
        "image": "http://example.com/storage/team/john-doe.jpg",
        "sort_order": 1,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-12-20T15:45:00.000000Z"
    }
}
```

**Response Example (Not Found):**

```json
{
    "success": false,
    "message": "Team member not found or is inactive",
    "error": "The requested team member does not exist or is not currently active"
}
```

**Status Codes:**

-   `200 OK` - Success
-   `404 Not Found` - Team member not found or inactive
-   `500 Internal Server Error` - Server error

---

### 3. Get Team Statistics

Retrieves statistical information about team members.

**Endpoint:** `GET /api/team/stats/overview`

**Response Example:**

```json
{
    "success": true,
    "message": "Team statistics retrieved successfully",
    "data": {
        "total": 15,
        "active": 12,
        "inactive": 3,
        "percentage_active": 80.0
    }
}
```

**Status Codes:**

-   `200 OK` - Success
-   `500 Internal Server Error` - Server error

---

## Data Model

### Team Member Object

| Field        | Type              | Description                                 |
| ------------ | ----------------- | ------------------------------------------- |
| `id`         | integer           | Unique identifier                           |
| `name`       | string            | Full name of the team member                |
| `position`   | string            | Job title/role                              |
| `location`   | string            | Geographic location                         |
| `image`      | string (URL)      | Full URL to the profile image               |
| `sort_order` | integer           | Display order (ascending)                   |
| `created_at` | string (ISO 8601) | Creation timestamp (only in detail view)    |
| `updated_at` | string (ISO 8601) | Last update timestamp (only in detail view) |

---

## React Integration Examples

### 1. Basic Fetch with useState/useEffect

```jsx
import { useState, useEffect } from "react";

function TeamSection() {
    const [team, setTeam] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch("http://your-domain.com/api/team")
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    setTeam(data.data);
                } else {
                    setError(data.message);
                }
                setLoading(false);
            })
            .catch((err) => {
                setError("Failed to fetch team members");
                setLoading(false);
            });
    }, []);

    if (loading) return <div>Loading team...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div className="team-grid">
            {team.map((member) => (
                <div key={member.id} className="team-card">
                    <img
                        src={member.image}
                        alt={member.name}
                        className="team-avatar"
                    />
                    <h3>{member.name}</h3>
                    <p className="position">{member.position}</p>
                    <p className="location">{member.location}</p>
                </div>
            ))}
        </div>
    );
}

export default TeamSection;
```

---

### 2. Using Axios

```jsx
import { useState, useEffect } from "react";
import axios from "axios";

const API_BASE_URL = "http://your-domain.com/api";

function TeamSection() {
    const [team, setTeam] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        axios
            .get(`${API_BASE_URL}/team`)
            .then((response) => {
                if (response.data.success) {
                    setTeam(response.data.data);
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
// hooks/useTeam.js
import { useState, useEffect } from "react";

const API_BASE_URL = "http://your-domain.com/api";

export function useTeam() {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchTeam = async () => {
            try {
                const response = await fetch(`${API_BASE_URL}/team`);
                const result = await response.json();

                if (result.success) {
                    setData(result.data);
                } else {
                    setError(result.message);
                }
            } catch (err) {
                setError("Failed to fetch team members");
            } finally {
                setLoading(false);
            }
        };

        fetchTeam();
    }, []);

    return { team: data, loading, error };
}

// Usage in component
import { useTeam } from "./hooks/useTeam";

function TeamSection() {
    const { team, loading, error } = useTeam();

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div className="team-grid">
            {team.map((member) => (
                <TeamCard key={member.id} member={member} />
            ))}
        </div>
    );
}
```

---

### 4. With React Query (Recommended for Production)

```jsx
// api/team.js
import axios from "axios";

const API_BASE_URL = "http://your-domain.com/api";

export const teamApi = {
    getAll: () => axios.get(`${API_BASE_URL}/team`),
    getById: (id) => axios.get(`${API_BASE_URL}/team/${id}`),
    getStats: () => axios.get(`${API_BASE_URL}/team/stats/overview`),
};

// components/TeamSection.jsx
import { useQuery } from "@tanstack/react-query";
import { teamApi } from "../api/team";

function TeamSection() {
    const { data, isLoading, error } = useQuery({
        queryKey: ["team"],
        queryFn: async () => {
            const response = await teamApi.getAll();
            return response.data.data;
        },
        staleTime: 1000 * 60 * 30, // 30 minutes
    });

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error.message}</div>;

    return (
        <div className="team-grid">
            {data?.map((member) => (
                <TeamCard key={member.id} member={member} />
            ))}
        </div>
    );
}
```

---

### 5. Team Member Detail View

```jsx
import { useState, useEffect } from "react";
import { useParams } from "react-router-dom";

function TeamMemberDetail() {
    const { id } = useParams();
    const [member, setMember] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch(`http://your-domain.com/api/team/${id}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    setMember(data.data);
                } else {
                    setError(data.message);
                }
                setLoading(false);
            })
            .catch((err) => {
                setError("Failed to fetch team member details");
                setLoading(false);
            });
    }, [id]);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;
    if (!member) return <div>Team member not found</div>;

    return (
        <div className="team-detail">
            <img src={member.image} alt={member.name} />
            <h1>{member.name}</h1>
            <h2>{member.position}</h2>
            <p className="location">📍 {member.location}</p>
            <div className="metadata">
                <p>
                    Member since:{" "}
                    {new Date(member.created_at).toLocaleDateString()}
                </p>
            </div>
        </div>
    );
}
```

---

### 6. TypeScript Types

```typescript
// types/team.ts
export interface TeamMember {
    id: number;
    name: string;
    position: string;
    location: string;
    image: string;
    sort_order: number;
    created_at?: string;
    updated_at?: string;
}

export interface TeamResponse {
    success: boolean;
    message: string;
    data: TeamMember[];
    count: number;
}

export interface TeamMemberResponse {
    success: boolean;
    message: string;
    data: TeamMember;
}

export interface TeamStatsResponse {
    success: boolean;
    message: string;
    data: {
        total: number;
        active: number;
        inactive: number;
        percentage_active: number;
    };
}

// Usage
import { TeamMember, TeamResponse } from "./types/team";

const fetchTeam = async (): Promise<TeamMember[]> => {
    const response = await fetch("http://your-domain.com/api/team");
    const result: TeamResponse = await response.json();
    return result.data;
};
```

---

### 7. Team Grid with Statistics

```jsx
import { useState, useEffect } from "react";

function TeamPage() {
    const [team, setTeam] = useState([]);
    const [stats, setStats] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                // Fetch both team and stats in parallel
                const [teamRes, statsRes] = await Promise.all([
                    fetch("http://your-domain.com/api/team"),
                    fetch("http://your-domain.com/api/team/stats/overview"),
                ]);

                const teamData = await teamRes.json();
                const statsData = await statsRes.json();

                if (teamData.success) setTeam(teamData.data);
                if (statsData.success) setStats(statsData.data);
            } catch (err) {
                console.error("Failed to fetch team data:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    if (loading) return <div>Loading...</div>;

    return (
        <div className="team-page">
            {/* Statistics Banner */}
            {stats && (
                <div className="team-stats">
                    <div className="stat-card">
                        <h3>{stats.active}</h3>
                        <p>Active Members</p>
                    </div>
                    <div className="stat-card">
                        <h3>{stats.percentage_active}%</h3>
                        <p>Team Engagement</p>
                    </div>
                </div>
            )}

            {/* Team Grid */}
            <div className="team-grid">
                {team.map((member) => (
                    <TeamCard key={member.id} member={member} />
                ))}
            </div>
        </div>
    );
}
```

---

### 8. Reusable Team Card Component

```jsx
// components/TeamCard.jsx
function TeamCard({ member }) {
    return (
        <div className="team-card">
            <div className="avatar-container">
                <img
                    src={member.image}
                    alt={member.name}
                    onError={(e) => {
                        e.target.src = "/placeholder-avatar.png"; // Fallback image
                    }}
                />
            </div>
            <div className="member-info">
                <h3 className="name">{member.name}</h3>
                <p className="position">{member.position}</p>
                <p className="location">
                    <span className="icon">📍</span>
                    {member.location}
                </p>
            </div>
        </div>
    );
}

export default TeamCard;
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
function TeamSection() {
    const [team, setTeam] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchTeam = async () => {
            try {
                const response = await fetch(`${API_BASE_URL}/team`);

                // Check if response is ok
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                // Check API success flag
                if (!result.success) {
                    throw new Error(
                        result.message || "Failed to fetch team members"
                    );
                }

                setTeam(result.data);
            } catch (err) {
                console.error("Error fetching team:", err);
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchTeam();
    }, []);

    if (loading) {
        return (
            <div className="loading-spinner">
                <p>Loading team members...</p>
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

    if (team.length === 0) {
        return <div className="no-data">No team members available</div>;
    }

    return (
        <div className="team-grid">
            {team.map((member) => (
                <TeamCard key={member.id} member={member} />
            ))}
        </div>
    );
}
```

---

## Combined API Service

```jsx
// services/api.js
const API_BASE_URL =
    process.env.REACT_APP_API_BASE_URL || "http://localhost:8000/api";

class ApiService {
    // Team endpoints
    async getTeam() {
        const response = await fetch(`${API_BASE_URL}/team`);
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        return data.data;
    }

    async getTeamMember(id) {
        const response = await fetch(`${API_BASE_URL}/team/${id}`);
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        return data.data;
    }

    async getTeamStats() {
        const response = await fetch(`${API_BASE_URL}/team/stats/overview`);
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        return data.data;
    }

    // Principles endpoints
    async getPrinciples() {
        const response = await fetch(`${API_BASE_URL}/principles`);
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        return data.data;
    }

    async getPrinciple(id) {
        const response = await fetch(`${API_BASE_URL}/principles/${id}`);
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        return data.data;
    }
}

export default new ApiService();

// Usage
import apiService from "./services/api";

function MyComponent() {
    useEffect(() => {
        apiService
            .getTeam()
            .then((data) => setTeam(data))
            .catch((err) => setError(err.message));
    }, []);
}
```

---

## CORS Configuration

If you encounter CORS errors, the backend may need to whitelist your React app's domain. Contact the backend administrator to add your frontend URL to the CORS allowed origins in `config/cors.php`.

**Common CORS Error:**

```
Access to fetch at 'http://backend.com/api/team' from origin 'http://localhost:3000'
has been blocked by CORS policy
```

---

## Testing the API

### Using Browser

Simply visit: `http://your-domain.com/api/team`

### Using cURL

```bash
# Get all team members
curl http://your-domain.com/api/team

# Get single team member
curl http://your-domain.com/api/team/1

# Get statistics
curl http://your-domain.com/api/team/stats/overview
```

### Using Postman

1. Create a new GET request
2. Enter URL: `http://your-domain.com/api/team`
3. Click "Send"

---

## Performance Best Practices

1. **Client-side caching**: Implement caching to minimize API calls
2. **Pagination**: If your team grows large, consider implementing pagination (not currently supported by API)
3. **Image optimization**: Use lazy loading for team member images
4. **Error boundaries**: Implement React error boundaries to gracefully handle failures

```jsx
// Example with lazy loading
import { LazyLoadImage } from "react-lazy-load-image-component";

function TeamCard({ member }) {
    return (
        <div className="team-card">
            <LazyLoadImage
                src={member.image}
                alt={member.name}
                effect="blur"
                placeholderSrc="/placeholder.jpg"
            />
            <h3>{member.name}</h3>
            <p>{member.position}</p>
        </div>
    );
}
```

---

## Rate Limiting

Currently, there's no rate limiting implemented. Consider implementing client-side request throttling/debouncing for production use.

---

## Related Documentation

For more information about the Team module:

-   `TEAM_MODULE_DOCUMENTATION.md` - Complete module documentation
-   `TEAM_QUICK_REFERENCE.md` - Quick reference guide
-   `TEAM_MODULE_ARCHITECTURE.md` - Architecture details
-   `PRINCIPLE_API_DOCUMENTATION.md` - Principles API (similar module)

---

## Changelog

-   **v1.0.0** - Initial API release with GET endpoints for team members
