<?php

namespace WpTracy;

use Tracy\Dumper;
use Tracy\IBarPanel;

/**
 * Common basic model for other WP panels
 *
 * @author Martin Hlaváč
 */
abstract class WpPanelBase implements IBarPanel
{
    /**
     * (HTML) content of tab for panel
     *
     * @param string $title
     * @param string $description
     * @param string $imageBase64
     * @return string
     */
    public function getSimpleTab($title = null, $description = null, $imageBase64 = null)
    {
        $output = "<span" . (!empty($description) ? " title=\"$description\"" : "") . ">";
        if (!empty($imageBase64)) {
            $output .= "<img src=\"data:image/png;base64,$imageBase64\" width=\"16\" height=\"16\" /> ";
        }
        if (!empty($title)) {
            $output .= $title;
        }
        $output .= "</span>";
        return $output;
    }

    /**
     * (HTML) table content of panel based on parameters array
     *
     * @param array $params
     * @param string $title
     * @return string
     */
    public function getTablePanel(array $params, $title = null)
    {
        $output = null;
        if (!empty($title)) {
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
     * @param mixed $object
     * @param string $title
     * @return string
     */
    public function getObjectPanel($object, $title = null)
    {
        $output = null;
        if (!empty($title)) {
            $output .= "<h1>$title</h1>";
        }
        $output .= "<div class=\"nette-inner\">";
        $output .= Dumper::toHtml($object, [Dumper::COLLAPSE => false]);
        $output .= "</div>";
        return $output;
    }
}
