<?php


namespace App\Http\Controllers;


use App\Models\Product;
use App\Support\ContactLinks;
use App\Support\TeamPhoto;
use Illuminate\View\View;


class PageController extends Controller
{
    public function home(): View
    {
        $catalog = Product::active()->orderBy('id')->get();

        return view('pages.home', [
            'signatureProducts' => $catalog->where('is_signature', true)->values(),
            'catalog'           => $catalog, // untuk carousel semua produk
        ]);
    }


    public function islamic(): View
    {
        return view('pages.islamic');
    }


    public function economic(): View
    {
        return view('pages.economic');
    }
    public function williamTanuwijaya(): View
    {
        return view('pages.william-tanuwijaya');
    }


    public function team(): View
    {
        $members = collect(config('lumeria.team', []))
            ->map(fn (array $member) => $this->presentMember($member))
            ->all();


        return view('pages.team', ['members' => $members]);
    }


    /**
     * Melengkapi data anggota dengan foto dan tautan kontak yang sudah dibersihkan.
     * Semua kunci opsional: anggota tanpa foto / kontak tetap tampil normal.
     */
    private function presentMember(array $member): array
    {
        $photo = TeamPhoto::resolve((string) ($member['key'] ?? ''), $member['photo'] ?? null);


        $instagram = ContactLinks::instagram($member['instagram'] ?? null);


        $whatsappText = __('team.contact.wa_message', ['brand' => __('common.brand')]);


        return array_merge($member, [
            // ?v=<waktu ubah file> supaya foto yang diganti langsung tampil (tidak tertahan cache browser)
            'photo_url' => $photo
                ? asset($photo) . '?v=' . (@filemtime(public_path($photo)) ?: 1)
                : null,
            'whatsapp_url' => ContactLinks::whatsapp(
                $member['whatsapp'] ?? null,
                (string) config('lumeria.contact.country_code', '62'),
                $whatsappText,
            ),
            'instagram_handle' => $instagram['handle'] ?? null,
            'instagram_url'    => $instagram['url'] ?? null,
            'portfolio_url'    => ContactLinks::url($member['portfolio'] ?? null),
        ]);
    }
}
