<?php

declare(strict_types=1);

namespace Akira\Sisp\Models;

use Akira\Sisp\Enums\TransactionStatus;
use Akira\Sisp\Traits\EncryptsAttributes;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $merchant_ref
 * @property string $merchant_session
 * @property float $amount
 * @property string $currency
 * @property TransactionStatus $status
 * @property string|null $transaction_code
 * @property string|null $transaction_id
 * @property string|null $message_type
 * @property string|null $response_code
 * @property string|null $merchant_response
 * @property string|null $fingerprint
 * @property array<string, mixed>|null $payload
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $customer_phone
 * @property string|null $customer_country
 * @property string|null $customer_city
 * @property string|null $customer_address
 * @property string|null $customer_postal_code
 * @property string $locale
 * @property CarbonInterface|null $cancelled_at
 * @property CarbonInterface|null $refunded_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Transaction extends Model
{
    use EncryptsAttributes;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'merchant_ref',
        'merchant_session',
        'amount',
        'currency',
        'status',
        'transaction_code',
        'transaction_id',
        'message_type',
        'response_code',
        'merchant_response',
        'fingerprint',
        'payload',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_country',
        'customer_city',
        'customer_address',
        'locale',
        'cancelled_at',
        'refunded_at',
        'customer_postal_code',
    ];

    public function getTable(): string
    {
        return config('sisp.tables.transactions', 'sisp_transactions');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'transaction_id');
    }

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'amount' => 'float',
            'status' => TransactionStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    protected function getFormattedAmountAttribute(): string
    {
        $formatted = number_format($this->amount, 0, ',', '.');

        return "{$formatted} ECV";
    }

    protected function encryptable(): array
    {
        return [
            'payload',
        ];
    }
}
