## Tugas 7 - CRUD Transaksi & Relasi Model
**Nama:** Fatkur Rohman Irham  
**Kampus:** Politeknik Negeri Banyuwangi  

Proyek ini merupakan kelanjutan dari sistem API Book Sales. Fokus pada tugas ini adalah mengimplementasikan operasi CRUD pada tabel **Transaksi**, mengatur relasi antar model dan tabel, serta menyesuaikan hak akses menggunakan middleware untuk Admin dan Customer.

## Instruksi Tugas

1. **CRUD Tabel Transaksi:** Menyelesaikan operasi CRUD untuk tabel Transaksi mengikuti panduan.
2. **Implementasi Relasi:**
   - Mengambil data dari relasi Model.
   - Mengatur relasi di file migration menggunakan `foreignId`.
3. **Atur Routing & Middleware:**
   - Endpoint **Create, Update, dan Show** pada Transaksi hanya dapat diakses oleh **Customer** yang sudah melakukan autentikasi.
   - Endpoint **Read All (Index) dan Destroy** pada Transaksi hanya dapat diakses oleh **Admin**.
4. **Testing:** Menggunakan Postman untuk melakukan testing aplikasi.
5. **Pengumpulan:**
   - Push repository ke GitHub.
   - Mengumpulkan Link Repository.
   - Mengumpulkan file `routes/api.php`.

---

# Hasil Pengerjaan Tugas

## Dokumentasi & Pengujian Endpoint (POSTMAN)

### Bagian 1: Hak Akses Customer (Create, Update, Show Transaksi)
*Pengujian ini dilakukan menggunakan Bearer Token dari user dengan role **Customer**.*

**1. Create Transaksi (POST)**
<img width="1432" height="880" alt="Screenshot 2026-04-28 233944" src="https://github.com/user-attachments/assets/b36ba4a6-fc85-4391-b025-b3c277c7b6e6" />
**2. Update Transaksi (PUT/PATCH)**
<img width="1420" height="790" alt="Screenshot 2026-04-28 234230" src="https://github.com/user-attachments/assets/2c2bead2-527f-48ff-8484-0a611397be2a" />
**3. Show Detail Transaksi (GET Detail)**
<img width="1431" height="905" alt="Screenshot 2026-04-28 234356" src="https://github.com/user-attachments/assets/bc82deaf-bdc0-4454-ba66-ab99c0288209" />
**4. Uji Penolakan Akses (Admin / Guest)**

<img width="713" height="329" alt="image" src="https://github.com/user-attachments/assets/574fc5a4-9eaa-4c67-a5b6-46902bd9f345" />

---

### Bagian 2: Hak Akses Admin (Read All, Destroy Transaksi)
*Pengujian ini dilakukan menggunakan Bearer Token dari user dengan role **Admin**.*

**1. Read All Transaksi (GET Index)**
<img width="1430" height="864" alt="Screenshot 2026-04-28 235152" src="https://github.com/user-attachments/assets/969958bd-98e4-4a47-bec4-f336c6490e57" />


**2. Destroy Transaksi (DELETE)**
<img width="1410" height="643" alt="Screenshot 2026-04-28 235225" src="https://github.com/user-attachments/assets/c75b1260-f3c9-452d-9283-c8028ffbf321" />


**3. Uji Penolakan Akses (Customer / Guest)**
<img width="718" height="359" alt="image" src="https://github.com/user-attachments/assets/3c411f7e-ff64-4345-b4cf-58291cf784bb" />


---

### Bagian 3: Implementasi Relasi (Migration & Model)

#### A. Relasi di File Migration (`foreignId`)

```php
 Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
```

#### B. Mengambil Data dari Relasi Model

```php
 $transaction = Transaction::with(['user', 'book'])->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found!'], 404);
        }

```

---

### Bagian 4: Cuplikan Kode Routing (`routes/api.php`)

```php
route::middleware('role:admin')->group(function () {
    Route::apiResource('/books', BookController::class)->only('store','update','destroy');
    Route::apiResource('/author', AuthorController::class)->only('store','update','destroy');
    Route::apiResource('/genre', GenreController::class)->only('store','update','destroy');
    route::apiResource('/transaction', TransactionController::class)->only('index','show','destroy');
    });
    route::middleware('role:customer')->group(function () {
    route::apiResource('/transaction', TransactionController::class)->only('store','update','show');
    });});

```
