<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\kategory;
use App\Models\Referral;
use App\Models\pencarian;
use App\Models\ReferralReward;
use App\Models\notifikasi;
use App\Models\pesanan;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;


class mainController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->hak_akses == 'User') {
                return redirect()->intended('panel');
            } else {
                return redirect()->intended('dashboard');
            }
        }

        return back()->with('gagal', 'Periksa Kombinasi email dan password anda');
    }

    public function dashboard()
    {
        return view('dashboard', [
            'kategoryes' => Categorie::get(),
            'users' => User::all(),
            'produks' => Product::all(),
            'pemesanans' => Product::all(),
            'pencarians' => Product::orderBy('created_at', 'ASC')->get(),
            'transaksis' => Product::orderBy('created_at', 'ASC')->limit(50)->get(),
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function getfile(Request $request)
    {
        $decryptFile = decrypt($request->file);
        // return $decryptFile;
        $filename = $decryptFile;
        $path = storage_path('app/public/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
    }

    public function index()
    {
        // $kategories = kategory::select('id', 'name')->get(); //
        $produks = produk::select('nama', 'harga', 'slug', 'id')
            ->with(['media:id,produk_id,file', 'kategory:id,nama'])
            ->paginate(50);
        return view('produk.lihatUser', [
            'produks' => $produks,
            'kategories' => $kategories,
        ]);
    }

    public function loginUser()
    {
        return view('login');
    }
    public function registerUser()
    {
        return view('register');
    }
    public function prosesRegisterUser(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email', 'unique:users,email'],
                'contact' => ['required', 'regex:/^[0-9]{10,15}$/'],
                'password' => ['required', 'min:6'],
                'password2' => ['required', 'same:password'],
                'captcha' => ['required', 'captcha'],
                'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
            ],
            [
                'captcha.captcha' => 'Kode captcha yang Anda masukkan salah.',
                'captcha.required' => 'Captcha harus diisi.',
            ],
        );

        if ($request->password != $request->password2) {
            return back()->with('gagal', 'Password Tidak Sama')->withInput();
        }

        //cek user yang ksaih rekomendasi
        // cari siapa yang punya kode referral (kalau ada)
        $referrer = null;
        if (!empty($credentials['referral_code'])) {
            $referrer = User::where('referral_code', !empty($credentials['referral_code']))->first();
        }

        $cek = User::create([
            'name' => $request->email,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'contact' => $request->contact,
            'hak_akses' => 'User',
            'referral_code' => $this->generateReferralCode(),
        ]);

        // kalau referral code valid → buat record di tabel referrals
        if ($referrer) {
            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_user_id' => $cek->id,
                'status' => 'pending',
            ]);
        }
        if ($cek) {
            return redirect('/login')->with('berhasil', 'Berhasil Registrasi Silahkan Login');
        } else {
            return back()->with('gaga', 'Gagal Registrasi Silahkan Hubungi Admin');
        }
    }

    private function generateReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    public function panel(Request $request)
    {
        $produk = pesanan::Filter($request->filter)
            ->orderby('created_at', 'desc')
            ->where('user_id', Auth::user()->id)
            ->paginate(10);
        return view('user.panel', [
            'pesanans' => $produk,
            'kategorys' => kategory::all(),
        ]);
    }

    public function masukanKeranjang(Request $request)
    {
        try {
            $request->validate([
                'produk' => 'required',
            ]);

            // Pastikan parameter produk valid
            $produkId = decrypt($request->produk);
        } catch (\Exception $e) {
            return back()->with('gagal', 'Produk tidak valid.');
        }

        // Cek apakah produk sudah ada di keranjang user
        $cek = Pesanan::where('user_id', Auth::id())->where('produk_id', $produkId)->where('status_pembayaran', 'keranjang')->first();

        if ($cek) {
            return back()->with('gagal', 'Produk sudah ada di keranjang.');
        }

        // Tambahkan produk ke keranjang
        $masukan = Pesanan::create([
            'user_id' => Auth::id(),
            'produk_id' => $produkId,
            'status_pembayaran' => 'keranjang',
        ]);

        if ($masukan) {
            return back()->with('berhasil', 'Produk berhasil dimasukkan ke keranjang.');
        }

        return back()->with('gagal', 'Gagal memasukkan produk ke keranjang.');
    }

    public function search(Request $request)
    {
        // Tangani kategori jika ada
        if ($request->filled('kategory')) {
            $kategory = Kategory::where('name', 'like', '%' . $request->kategory . '%')->first();

            if ($kategory) {
                $kategory->increment('dicari'); // lebih singkat daripada ambil + update
            }
        }

        // Tangani parameter pencarian
        if ($request->filled('parameter')) {
            // Simpan query pencarian jika belum ada
            pencarian::firstOrCreate(['parameter' => $request->parameter]);

            // Cari produk menggunakan Laravel Scout / Meilisearch
            $produkList = Produk::search($request->parameter)->get();
        } else {
            // Jika tidak ada parameter, tampilkan semua produk atau kosongkan
            $produkList = collect(); // koleksi kosong
        }

        return view('produk.search', [
            'produks' => $produkList,
            // 'kategorys' => Kategory::all(),
        ]);
    }

    function profile()
    {
        return view('profile.index', [
            'kategorys' => kategory::all(),
        ]);
    }

    function updateProfile(Request $request)
    {
        user::find(Auth::user()->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);

        return back();
    }

    function forgotPassword()
    {
        return view('user.forgotPassword');
    }
    function kirimEmail(Request $request)
    {
        $cek = User::firstWhere('email', $request->email);

        if (!$cek) {
            return back()->with('gagal', 'Email Tidak Ditemukan');
        }
        $details = [
            'email' => $request->email,
            'link' => env('APP_URL') . '/reset-password?auth=' . encrypt($cek->id),
        ];

        Mail::send('email.thameplateemail', ['details' => $details], function ($message) use ($details) {
            $message->to($details['email'])->subject('Forgot Password');
        });

        return 'Link Reset Password Di kirim Ke email';
    }

    function downloadQris(Request $request)
    {
        $decryptFile = decrypt($request->file);
        // return $decryptFile;
        $filename = $decryptFile;
        $path = storage_path('app/public/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response()->download($path);
    }

    function resetPassword(Request $request)
    {
        return view('resetpassword', [
            'auth' => $request->auth,
        ]);
    }

    function reset(Request $request)
    {
        if ($request->password != $request->password2) {
            return back()->with('gagal', 'Password Tidak Sama')->withInput();
        }
        $cek = User::find(decrypt($request->auth))->update([
            'password' => bcrypt($request->password),
        ]);

        if ($cek) {
            return redirect('/login')->with('berhasil', 'Berhasil Silahkan Login');
        } else {
            return redirect('/login')->with('gagal', 'Berhasil Silahkan Login');
        }
    }

    function maintenance()
    {
        return view('maintenance');
    }
    function notifikasi()
    {
        // Ambil semua notifikasi user terbaru dulu
        $notifikasi = notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('user.notifikasi', compact('notifikasi'));
    }

    function listKategory()
    {
        $kategoris = kategory::with([
            'produk' => function ($query) {
                $query->inRandomOrder()->limit(2);
            },
        ])->get();

        return view('kategory.list', compact('kategoris'));
    }
    function team(Request $request)
    {
        $user = Auth::user();

        $referralCount = $user->refferals()->where('status', 'active')->count();
        $points = $user->referralPoints->points ?? 0;
        $rewards = ReferralReward::all();
        $referrals = $user->refferals()->with('referredUser')->get();
        return view('user.akun', compact('user', 'referralCount', 'points', 'rewards', 'referrals'));
    }
}
