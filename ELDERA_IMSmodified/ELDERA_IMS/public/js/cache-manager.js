/**
 * Client-Side Cache Manager for ELDERA IMS
 * Provides localStorage caching for frequently accessed data
 */
class CacheManager {
    constructor() {
        this.cachePrefix = 'eldera_cache_';
        this.defaultTTL = 5 * 60 * 1000; // 5 minutes in milliseconds
        this.maxCacheSize = 10 * 1024 * 1024; // 10MB max cache size
    }

    /**
     * Generate cache key
     */
    generateKey(key) {
        return this.cachePrefix + key;
    }

    /**
     * Check if cache entry is valid
     */
    isValid(cacheData) {
        if (!cacheData || !cacheData.timestamp || !cacheData.data) {
            return false;
        }
        
        const now = Date.now();
        const age = now - cacheData.timestamp;
        return age < cacheData.ttl;
    }

    /**
     * Get data from cache
     */
    get(key) {
        try {
            const cacheKey = this.generateKey(key);
            const cached = localStorage.getItem(cacheKey);
            
            if (!cached) {
                return null;
            }

            const cacheData = JSON.parse(cached);
            
            if (this.isValid(cacheData)) {
                console.log(`Cache hit: ${key}`);
                return cacheData.data;
            } else {
                console.log(`Cache expired: ${key}`);
                this.remove(key);
                return null;
            }
        } catch (error) {
            console.error('Cache get error:', error);
            return null;
        }
    }

    /**
     * Set data in cache
     */
    set(key, data, ttl = this.defaultTTL) {
        try {
            const cacheKey = this.generateKey(key);
            const cacheData = {
                data: data,
                timestamp: Date.now(),
                ttl: ttl
            };

            const serialized = JSON.stringify(cacheData);
            
            // Check cache size limit
            if (this.getCacheSize() + serialized.length > this.maxCacheSize) {
                this.cleanup();
            }

            localStorage.setItem(cacheKey, serialized);
            console.log(`Cache set: ${key}`);
        } catch (error) {
            console.error('Cache set error:', error);
        }
    }

    /**
     * Remove specific cache entry
     */
    remove(key) {
        try {
            const cacheKey = this.generateKey(key);
            localStorage.removeItem(cacheKey);
            console.log(`Cache removed: ${key}`);
        } catch (error) {
            console.error('Cache remove error:', error);
        }
    }

    /**
     * Clear all cache entries
     */
    clear() {
        try {
            const keys = Object.keys(localStorage);
            keys.forEach(key => {
                if (key.startsWith(this.cachePrefix)) {
                    localStorage.removeItem(key);
                }
            });
            console.log('All cache cleared');
        } catch (error) {
            console.error('Cache clear error:', error);
        }
    }

    /**
     * Get current cache size
     */
    getCacheSize() {
        let size = 0;
        try {
            const keys = Object.keys(localStorage);
            keys.forEach(key => {
                if (key.startsWith(this.cachePrefix)) {
                    size += localStorage.getItem(key).length;
                }
            });
        } catch (error) {
            console.error('Cache size calculation error:', error);
        }
        return size;
    }

    /**
     * Cleanup expired entries and oldest entries if over limit
     */
    cleanup() {
        try {
            const keys = Object.keys(localStorage);
            const cacheEntries = [];

            // Collect all cache entries with metadata
            keys.forEach(key => {
                if (key.startsWith(this.cachePrefix)) {
                    try {
                        const cached = localStorage.getItem(key);
                        const cacheData = JSON.parse(cached);
                        cacheEntries.push({
                            key: key,
                            timestamp: cacheData.timestamp,
                            size: cached.length
                        });
                    } catch (e) {
                        // Remove corrupted entries
                        localStorage.removeItem(key);
                    }
                }
            });

            // Sort by timestamp (oldest first)
            cacheEntries.sort((a, b) => a.timestamp - b.timestamp);

            // Remove oldest entries until under limit
            let currentSize = this.getCacheSize();
            for (let entry of cacheEntries) {
                if (currentSize <= this.maxCacheSize * 0.8) { // Keep 80% of limit
                    break;
                }
                localStorage.removeItem(entry.key);
                currentSize -= entry.size;
            }

            console.log('Cache cleanup completed');
        } catch (error) {
            console.error('Cache cleanup error:', error);
        }
    }

    /**
     * Cache API response
     */
    async cacheApiResponse(url, options = {}, ttl = this.defaultTTL) {
        const cacheKey = `api_${btoa(url)}_${btoa(JSON.stringify(options))}`;
        
        // Try to get from cache first
        const cached = this.get(cacheKey);
        if (cached) {
            return cached;
        }

        // Fetch from API
        try {
            const response = await fetch(url, options);
            const data = await response.json();
            
            // Cache successful responses
            if (response.ok) {
                this.set(cacheKey, data, ttl);
            }
            
            return data;
        } catch (error) {
            console.error('API fetch error:', error);
            throw error;
        }
    }

    /**
     * Cache form data
     */
    cacheFormData(formId, data) {
        const cacheKey = `form_${formId}`;
        this.set(cacheKey, data, 30 * 60 * 1000); // 30 minutes
    }

    /**
     * Get cached form data
     */
    getCachedFormData(formId) {
        const cacheKey = `form_${formId}`;
        return this.get(cacheKey);
    }

    /**
     * Cache search results
     */
    cacheSearchResults(query, results) {
        const cacheKey = `search_${btoa(query)}`;
        this.set(cacheKey, results, 10 * 60 * 1000); // 10 minutes
    }

    /**
     * Get cached search results
     */
    getCachedSearchResults(query) {
        const cacheKey = `search_${btoa(query)}`;
        return this.get(cacheKey);
    }
}

// Initialize global cache manager
window.CacheManager = new CacheManager();

// Auto-cleanup on page load
document.addEventListener('DOMContentLoaded', function() {
    window.CacheManager.cleanup();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    // Keep cache for next session
});
