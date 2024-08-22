<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CutController extends Controller
{
    public function optimize(Request $request)
    {
        $paperWidth = $request->input('paper_width');
        $paperHeight = $request->input('paper_height');
        $cuts = $request->input('cuts');

        usort($cuts, function ($a, $b) {
            return $b['height'] <=> $a['height'];
        });

        $layouts = [];
        $currentSheet = $this->initializeSheet($paperWidth, $paperHeight);

        foreach ($cuts as $cut) {
            $placed = $this->placeCutOnSheet($currentSheet, $cut);
            if (!$placed) {
                $layouts[] = $currentSheet;
                $currentSheet = $this->initializeSheet($paperWidth, $paperHeight);
                $this->placeCutOnSheet($currentSheet, $cut);
            }
        }

        $layouts[] = $currentSheet;
        return response()->json($layouts);
    }

    private function initializeSheet($width, $height)
    {
        return [
            'width' => $width,
            'height' => $height,
            'cuts' => []
        ];
    }

    private function placeCutOnSheet(&$sheet, $cut)
    {
        if (empty($sheet['cuts'])) {
            $sheet['cuts'][] = [
                'width' => $cut['width'],
                'height' => $cut['height'],
                'x' => 0,
                'y' => 0
            ];
            return true;
        }

        for ($y = 0; $y <= $sheet['height'] - $cut['height']; $y++) {
            for ($x = 0; $x <= $sheet['width'] - $cut['width']; $x++) {
                if ($this->canPlaceCut($sheet, $cut, $x, $y)) {
                    $sheet['cuts'][] = [
                        'width' => $cut['width'],
                        'height' => $cut['height'],
                        'x' => $x,
                        'y' => $y
                    ];
                    return true;
                }
            }
        }
        return false;
    }

    private function canPlaceCut($sheet, $cut, $x, $y)
    {
        if ($x + $cut['width'] > $sheet['width'] || $y + $cut['height'] > $sheet['height']) {
            return false;
        }

        foreach ($sheet['cuts'] as $placedCut) {
            if (
                $x < $placedCut['x'] + $placedCut['width'] &&
                $x + $cut['width'] > $placedCut['x'] &&
                $y < $placedCut['y'] + $placedCut['height'] &&
                $y + $cut['height'] > $placedCut['y']
            ) {
                return false;
            }
        }

        return true;
    }
}
