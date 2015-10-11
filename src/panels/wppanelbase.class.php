<?php

namespace WpTracy;

use Tracy,
    Tracy\IBarPanel,
    Tracy\Dumper;

/**
 * Common basic model for other WP panels 
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
abstract class WpPanelBase implements IBarPanel {

    /**
     * (HTML) content of tab for panel
     * 
     * @author Martin Hlaváč
     * @link http://www.ktstudio.cz/
     * 
     * @param string $title
     * @param string $description
     * @param string $imageBase64
     * @return string
     */
    public function getSimpleTab($title = null, $description = null, $imageBase64 = null) {
        $output = "<span" . (self::issetAndNotEmpty($description) ? " title=\"$description\"" : "") . ">";
        if (self::issetAndNotEmpty($imageBase64)) {
            $output .= "<img src=\"data:image/png;base64,$imageBase64\" /> ";
        }
        if (self::issetAndNotEmpty($title)) {
            $output .= $title;
        }
        $output .= "</span>";
        return $output;
    }

    /**
     * (HTML) table content of panel based on parameters array
     * 
     * @author Martin Hlaváč
     * @link http://www.ktstudio.cz/
     * 
     * @param array $params
     * @param string $title
     * @return string
     */
    public function getTablePanel(array $params, $title = null) {
        $output = null;
        if (self::issetAndNotEmpty($title)) {
            $output .= "<h1>$title</h1>";
        }
        $output .= "<div class=\"nette-inner\">";
        $output .= "<table>";
        $output .= "<thead>";
        $output .= "<tr>";
        $output .= "<th>" . __("Parameter") . "</th>";
        $output .= "<th>" . __("Value") . "</th>";
        $output .= "</tr>";
        $output .= "</thead>";
        foreach ($params as $key => $value) {
            $output .= "<tr>";
            $output .= "<td>$key:</td>";
            $output .= "<td>$value</td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        $output .= "</div>";
        return $output;
    }

    /**
     * (HTML) content of panel based on object 
     * 
     * @author Martin Hlaváč
     * @link http://www.ktstudio.cz/
     * 
     * @param mixed $object
     * @param string $title
     * @return string
     */
    public function getObjectPanel($object, $title = null) {
        $output = null;
        if (self::issetAndNotEmpty($title)) {
            $output .= "<h1>$title</h1>";
        }
        $output .= "<div class=\"nette-inner\">";
        $output .= Dumper::toHtml($object, array(Dumper::COLLAPSE => false));
        $output .= "</div>";
        return $output;
    }

    /**
     * Check if the value is assigned and if it's not empty
     * 
     * @author Martin Hlaváč
     * @link http://www.ktstudio.cz/
     * 
     * @param mixed $value
     * @return boolean
     */
    public static function issetAndNotEmpty($value) {
        return isset($value) && !empty($value);
    }

}
