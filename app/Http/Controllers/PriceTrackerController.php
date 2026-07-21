<?php

namespace App\Http\Controllers;

use App\Models\PriceReference;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PriceTrackerController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input(
            'periode',
            PriceReference::max('periode')
        );

        $komoditas = $request->input('komoditas');

        $tracker = $this->buildTracker($periode, $komoditas);

        return view('price-tracker.index', [
            'tracker' => $tracker,
            'periode' => $periode,
            'periodes' => $this->getPeriodes(),
            'komoditas' => $komoditas,
            'komoditasList' => $this->getKomoditasList(),
        ]);
    }

    private function buildTracker(string $periode, ?string $komoditas): array
    {
        $produsen = $this->getHargaProdusen($periode, $komoditas);
        $konsumen = $this->getHargaKonsumen($periode, $komoditas);

        $tracker = [];

        foreach ($produsen as $kategori => $item) {

            if (!isset($konsumen[$kategori])) {

                $tracker[] = [
                    'kategori_komoditas' => $kategori,
                    'periode' => $periode,
                    'harga_produsen' => (float) $item->harga_produsen,
                    'harga_konsumen' => null,
                    'margin' => null,
                    'persentase_margin' => null,
                ];

                continue;
            }

            $hargaProdusen = (float) $item->harga_produsen;
            $hargaKonsumen = (float) $konsumen[$kategori]->harga;

            $margin = $hargaKonsumen - $hargaProdusen;

            $tracker[] = [
                'kategori_komoditas' => $kategori,
                'periode' => $periode,
                'harga_produsen' => $hargaProdusen,
                'harga_konsumen' => $hargaKonsumen,
                'margin' => $margin,
                'persentase_margin' => round(
                    ($margin / $hargaProdusen) * 100,
                    2
                ),
            ];
        }

        return $tracker;
    }

    private function getHargaProdusen(string $periode, ?string $komoditas): Collection
    {
        return PriceReference::query()
            ->where('sumber', 'bps')
            ->where('periode', $periode)
            ->when(
                $komoditas,
                fn ($query) => $query->where('kategori_komoditas', $komoditas)
            )
            ->selectRaw('
                kategori_komoditas,
                AVG(harga) AS harga_produsen
            ')
            ->groupBy('kategori_komoditas')
            ->get()
            ->keyBy('kategori_komoditas');
    }

    private function getHargaKonsumen(string $periode, ?string $komoditas): Collection
    {
        return PriceReference::query()
            ->where('sumber', 'siskaperbapo')
            ->where('periode', $periode)
            ->when(
                $komoditas,
                fn ($query) => $query->where('kategori_komoditas', $komoditas)
            )
            ->get()
            ->keyBy('kategori_komoditas');
    }

    private function getPeriodes(): Collection
    {
        return PriceReference::query()
            ->select('periode')
            ->distinct()
            ->orderByDesc('periode')
            ->pluck('periode');
    }

    private function getKomoditasList(): Collection
    {
        return PriceReference::query()
            ->select('kategori_komoditas')
            ->distinct()
            ->orderBy('kategori_komoditas')
            ->pluck('kategori_komoditas');
    }
}