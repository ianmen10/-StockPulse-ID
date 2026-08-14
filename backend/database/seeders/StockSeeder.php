<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Initial IDX master data.
     *
     * @var array<int, array{symbol: string, name: string, sector: string}>
     */
    private const STOCKS = [
        ['symbol' => 'BBCA.JK', 'name' => 'Bank Central Asia Tbk', 'sector' => 'Financials'],
        ['symbol' => 'BBRI.JK', 'name' => 'Bank Rakyat Indonesia Tbk', 'sector' => 'Financials'],
        ['symbol' => 'BMRI.JK', 'name' => 'Bank Mandiri Tbk', 'sector' => 'Financials'],
        ['symbol' => 'BBNI.JK', 'name' => 'Bank Negara Indonesia Tbk', 'sector' => 'Financials'],
        ['symbol' => 'TLKM.JK', 'name' => 'Telkom Indonesia Tbk', 'sector' => 'Telecommunications'],
        ['symbol' => 'ASII.JK', 'name' => 'Astra International Tbk', 'sector' => 'Industrials'],
        ['symbol' => 'ANTM.JK', 'name' => 'Aneka Tambang Tbk', 'sector' => 'Materials'],
        ['symbol' => 'GOTO.JK', 'name' => 'GoTo Gojek Tokopedia Tbk', 'sector' => 'Technology'],
        ['symbol' => 'ICBP.JK', 'name' => 'Indofood CBP Sukses Makmur Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'INDF.JK', 'name' => 'Indofood Sukses Makmur Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'PGAS.JK', 'name' => 'Perusahaan Gas Negara Tbk', 'sector' => 'Utilities'],
        ['symbol' => 'UNVR.JK', 'name' => 'Unilever Indonesia Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'ADRO.JK', 'name' => 'Alamtri Resources Indonesia Tbk', 'sector' => 'Energy'],
        ['symbol' => 'PTBA.JK', 'name' => 'Bukit Asam Tbk', 'sector' => 'Energy'],
        ['symbol' => 'INCO.JK', 'name' => 'Vale Indonesia Tbk', 'sector' => 'Materials'],
        ['symbol' => 'MDKA.JK', 'name' => 'Merdeka Copper Gold Tbk', 'sector' => 'Materials'],
        ['symbol' => 'AKRA.JK', 'name' => 'AKR Corporindo Tbk', 'sector' => 'Energy'],
        ['symbol' => 'SMGR.JK', 'name' => 'Semen Indonesia Tbk', 'sector' => 'Materials'],
        ['symbol' => 'KLBF.JK', 'name' => 'Kalbe Farma Tbk', 'sector' => 'Healthcare'],
        ['symbol' => 'TOWR.JK', 'name' => 'Sarana Menara Nusantara Tbk', 'sector' => 'Telecommunications'],
        ['symbol' => 'BUMI.JK', 'name' => 'Bumi Resources Tbk', 'sector' => 'Energy'],
        ['symbol' => 'EXCL.JK', 'name' => 'XL Axiata Tbk', 'sector' => 'Telecommunications'],
        ['symbol' => 'ISAT.JK', 'name' => 'Indosat Ooredoo Hutchison Tbk', 'sector' => 'Telecommunications'],
        ['symbol' => 'AALI.JK', 'name' => 'Astra Agro Lestari Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'CPIN.JK', 'name' => 'Charoen Pokphand Indonesia Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'TINS.JK', 'name' => 'Timah Tbk', 'sector' => 'Materials'],
        ['symbol' => 'MYOR.JK', 'name' => 'Mayora Indah Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'ACES.JK', 'name' => 'Ace Hardware Indonesia Tbk', 'sector' => 'Consumer Discretionary'],
        ['symbol' => 'SIDO.JK', 'name' => 'Industri Jamu dan Farmasi Sido Muncul Tbk', 'sector' => 'Healthcare'],
        ['symbol' => 'UNTR.JK', 'name' => 'United Tractors Tbk', 'sector' => 'Industrials'],
        ['symbol' => 'HRUM.JK', 'name' => 'Harum Energy Tbk', 'sector' => 'Energy'],
        ['symbol' => 'ITMG.JK', 'name' => 'Indo Tambangraya Megah Tbk', 'sector' => 'Energy'],
        ['symbol' => 'WIKA.JK', 'name' => 'Wijaya Karya Tbk', 'sector' => 'Industrials'],
        ['symbol' => 'ADHI.JK', 'name' => 'Adhi Karya Tbk', 'sector' => 'Industrials'],
        ['symbol' => 'BRPT.JK', 'name' => 'Barito Pacific Tbk', 'sector' => 'Energy'],
        ['symbol' => 'GGRM.JK', 'name' => 'Gudang Garam Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'HMSP.JK', 'name' => 'HM Sampoerna Tbk', 'sector' => 'Consumer Staples'],
        ['symbol' => 'JSMR.JK', 'name' => 'Jasa Marga Tbk', 'sector' => 'Industrials'],
        ['symbol' => 'MAPI.JK', 'name' => 'Mitra Adiperkasa Tbk', 'sector' => 'Consumer Discretionary'],
        ['symbol' => 'ERAA.JK', 'name' => 'Erajaya Swasembada Tbk', 'sector' => 'Consumer Discretionary'],
        ['symbol' => 'TBIG.JK', 'name' => 'Tower Bersama Infrastructure Tbk', 'sector' => 'Telecommunications'],
        ['symbol' => 'PWON.JK', 'name' => 'Pakuwon Jati Tbk', 'sector' => 'Real Estate'],
        ['symbol' => 'BSDE.JK', 'name' => 'Bumi Serpong Damai Tbk', 'sector' => 'Real Estate'],
        ['symbol' => 'CTRA.JK', 'name' => 'Ciputra Development Tbk', 'sector' => 'Real Estate'],
        ['symbol' => 'MNCN.JK', 'name' => 'Media Nusantara Citra Tbk', 'sector' => 'Communication Services'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::STOCKS as $stock) {
            Stock::updateOrCreate(
                ['symbol' => $stock['symbol']],
                ['name' => $stock['name'], 'sector' => $stock['sector'], 'is_active' => true]
            );
        }
    }
}
