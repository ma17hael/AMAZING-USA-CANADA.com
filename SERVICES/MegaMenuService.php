<?php

class MegaMenuService
{
    /**
     * Récupère tout le mega menu (countries + locations)
     */
    public static function get(PDO $db, string $langID, string $fallbackID): array
    {
        return [
            'countries' => self::getCountries($db, $langID, $fallbackID),
            'locations' => self::getLocations($db, $langID, $fallbackID),
        ];
    }

    /**
     * COUNTRIES
     */
    private static function getCountries(PDO $db, string $langID, string $fallbackID): array
    {
        $key = "countries_{$langID}_{$fallbackID}";

        $cached = cache_get($key);
        if (is_array($cached)) {
            return $cached;
        }

        $sql = "
            SELECT 
                c.ISOCode,
                COALESCE(ct.Name, cf.Name, c.ISOCode) AS Name
            FROM countries c
            LEFT JOIN country_translations ct
                ON ct.CountryID = c.CountryID
                AND ct.Lang = :langID
            LEFT JOIN country_translations cf
                ON cf.CountryID = c.CountryID
                AND cf.Lang = :fallbackID
            ORDER BY Name ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':langID' => $langID,
            ':fallbackID' => $fallbackID
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        cache_set($key, $data, 3600);

        return $data;
    }

    /**
     * LOCATIONS
     */
    private static function getLocations(PDO $db, string $langID, string $fallbackID): array
    {
        $key = "locations_{$langID}_{$fallbackID}";

        $cached = cache_get($key);
        if (is_array($cached)) {
            return $cached;
        }

        $sql = "
            SELECT 
                l.LocationID,
                COALESCE(lt.Label, lf.Label, '') AS Label
            FROM locations l
            LEFT JOIN location_translations lt
                ON lt.LocationID = l.LocationID
                AND lt.Lang = :langID
            LEFT JOIN location_translations lf
                ON lf.LocationID = l.LocationID
                AND lf.Lang = :fallbackID
            ORDER BY Label ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':langID' => $langID,
            ':fallbackID' => $fallbackID
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        cache_set($key, $data, 3600);

        return $data;
    }
}