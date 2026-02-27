<?php
/**
 * Theme Model
 * 
 * Handles theme settings and customization
 */

class Theme
{
    private $db;
    private $settings = [];
    private static $instance = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->loadSettings();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load all theme settings
     */
    private function loadSettings()
    {
        $results = $this->db->fetchAll("SELECT setting_key, setting_value FROM theme_settings");

        foreach ($results as $row) {
            $this->settings[$row['setting_key']] = $row['setting_value'];
        }
    }

    /**
     * Get setting value
     */
    public function get($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Get all settings
     */
    public function getAll()
    {
        return $this->settings;
    }

    /**
     * Update setting
     */
    public function set($key, $value)
    {
        $existing = $this->db->fetchOne(
            "SELECT id FROM theme_settings WHERE setting_key = ?",
            [$key]
        );

        if ($existing) {
            $this->db->update('theme_settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
        } else {
            $this->db->insert('theme_settings', [
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }

        $this->settings[$key] = $value;

        return ['success' => true, 'message' => 'Setting updated'];
    }

    /**
     * Update multiple settings
     */
    public function updateMultiple($settings)
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }

        return ['success' => true, 'message' => 'Settings updated'];
    }

    /**
     * Generate CSS variables from theme settings
     */
    public function generateCSS()
    {
        $css = ":root {\n";
        $css .= "  --primary-color: " . $this->get('primary_color', '#2563eb') . ";\n";
        $css .= "  --secondary-color: " . $this->get('secondary_color', '#7c3aed') . ";\n";
        $css .= "  --font-family: '" . $this->get('font_family', 'Inter') . "', sans-serif;\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Get banners
     */
    public function getBanners($position = null)
    {
        $sql = "SELECT * FROM banners WHERE status = 'active'";
        $params = [];

        if ($position) {
            $sql .= " AND position = ?";
            $params[] = $position;
        }

        $sql .= " ORDER BY sort_order ASC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get FAQs
     */
    public function getFAQs()
    {
        return $this->db->fetchAll(
            "SELECT * FROM faqs WHERE status = 'active' ORDER BY sort_order ASC"
        );
    }

    /**
     * Get testimonials
     */
    public function getTestimonials()
    {
        return $this->db->fetchAll(
            "SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order ASC"
        );
    }

    /**
     * Get page by slug
     */
    public function getPage($slug)
    {
        return $this->db->fetchOne(
            "SELECT * FROM pages WHERE slug = ? AND status = 'active'",
            [$slug]
        );
    }
}
